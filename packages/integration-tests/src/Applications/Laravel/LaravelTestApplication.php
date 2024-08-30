<?php
namespace Apie\IntegrationTests\Applications\Laravel;

use Apie\Common\Events\AddAuthenticationCookie;
use Apie\Common\IntegrationTestLogger;
use Apie\Common\ValueObjects\DecryptedAuthenticatedUser;
use Apie\Common\Wrappers\TextEncrypter;
use Apie\Core\Other\FileWriterInterface;
use Apie\Core\Other\MockFileWriter;
use Apie\IntegrationTests\Concerns\RunApplicationTest;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\LaravelApie\ApieServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;
use Orchestra\Testbench\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LaravelTestApplication extends TestCase implements TestApplicationInterface
{
    use RunApplicationTest;

    public function __construct(
        private readonly ApplicationConfig $applicationConfig,
        private readonly BoundedContextConfig $boundedContextConfig
    ) {
        parent::__construct();
    }

    public function getConsoleApplication(): Application
    {
        $application = new \Illuminate\Console\Application(
            $this->getServiceContainer(),
            $this->getServiceContainer()->get(\Illuminate\Contracts\Events\Dispatcher::class),
            'test'
        );
        return $application;
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->applicationConfig;
    }

    protected function defineEnvironment($app): void
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set('app.key', 'base64:/aNEFWQbsYwDslb4Xw1RKKj9oCdZdbNhvcyUpVgXPz4=');
            $config->set('apie.encryption_key', 'test');
            $config->set(
                'apie.bounded_contexts',
                $this->boundedContextConfig->toArray()
            );
            $config->set(
                'apie.scan_bounded_contexts',
                []
            );
            $config->set(
                'apie.datalayers',
                [
                    'default_datalayer' => $this->applicationConfig->getDatalayerImplementation()->name,
                ]
            );
            $config->set(
                'apie.doctrine',
                [
                    'build_once' => false,
                    'run_migrations' => true,
                    'connection_params' => [
                        'driver' => 'pdo_sqlite'
                    ]
                ]
            );
        });
    }

    /**
     * Boot application. Should be called at the start of the test.
     */
    public function bootApplication(): void
    {
        IntegrationTestLogger::resetLoggedException();
        $this->setUp();
        $this->session([]);
        if (getenv('PHPUNIT_LOG_INTEGRATION_OUTPUT')) {
            $this->withoutExceptionHandling([
                NotFoundHttpException::class
            ]);
        }
        $this->app->instance(FileWriterInterface::class, new MockFileWriter());
        unset($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME]);
    }

    /**
     * Gets service container of application. Should be used as little as possible.
     */
    public function getServiceContainer(): ContainerInterface
    {
        return $this->app;
    }

    /**
     * Cleans up application. Should be called at the end of the test.
     */
    public function cleanApplication(): void
    {
        $this->tearDown();
    }

    /**
     * Does a HTTP request on the application and returns the response.
     */
    public function httpRequestGet(string $uri): ResponseInterface
    {
        $testResponse = $this->get($uri);
        $laravelResponse = $testResponse->baseResponse;
        return $this->handleResponse($laravelResponse);
    }

    private function handleResponse(HttpFoundationResponse $laravelResponse): ResponseInterface
    {
        $cookie = $laravelResponse->headers->getCookies(
            ResponseHeaderBag::COOKIES_ARRAY
        )[""]["/"][AddAuthenticationCookie::COOKIE_NAME] ?? null;
        if ($cookie !== null) {
            $cookie = Cookie::fromString($cookie)->getValue();
        }
        if ($cookie) {
            $this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME] = $cookie;
        } else {
            unset($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME]);
        }

        $psrFactory = new NyholmPsr17Factory();
        $factory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);
        return $factory->createResponse($laravelResponse);
    }

    protected function getPackageProviders($app): array
    {
        return [ApieServiceProvider::class];
    }

    public function httpRequest(TestRequestInterface $testRequest): ResponseInterface
    {
        $psrRequest = $testRequest->getRequest();
        $factory = new HttpFoundationFactory();
        $sfRequest = $factory->createRequest($psrRequest);
        $laravelRequest = Request::createFromBase($sfRequest);
        if (isset($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME])) {
            $laravelRequest->cookies->set(AddAuthenticationCookie::COOKIE_NAME, $this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME]);
        }
        $laravelResponse = $this->app->make(HttpKernel::class)->handle($laravelRequest);
        return $this->handleResponse($laravelResponse);
    }

    public function loginAs(DecryptedAuthenticatedUser $user): void
    {
        $textEncrypter = new TextEncrypter('test');
        $this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME] = $textEncrypter->encrypt($user->toNative());
    }

    /**
     * Forget that you are logged in.
     */
    public function logout(): void
    {
        Auth::logout();
        unset($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME]);
    }

    public function getLoggedInAs(): ?DecryptedAuthenticatedUser
    {
        if (empty($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME])) {
            return null;
        }
        $textEncrypter = new TextEncrypter('test');
        return DecryptedAuthenticatedUser::fromNative(
            $textEncrypter->decrypt($this->defaultCookies[AddAuthenticationCookie::COOKIE_NAME]),
        );
    }
}
