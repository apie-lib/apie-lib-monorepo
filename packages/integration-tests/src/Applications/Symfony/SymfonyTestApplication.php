<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Apie\Common\Events\AddAuthenticationCookie;
use Apie\Common\IntegrationTestLogger;
use Apie\Common\ValueObjects\DecryptedAuthenticatedUser;
use Apie\Common\Wrappers\TextEncrypter;
use Apie\IntegrationTests\Concerns\RunApplicationTest;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SymfonyTestApplication implements TestApplicationInterface
{
    use RunApplicationTest;

    private ?SymfonyTestingKernel $kernel = null;

    private ?string $authenticationCookie = null;

    public function __construct(
        private readonly ApplicationConfig $applicationConfig,
        private readonly BoundedContextConfig $boundedContextConfig
    ) {
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->applicationConfig;
    }

    public function getConsoleApplication(): Application
    {
        $application = new ConsoleApplication($this->kernel);
        $application->setAutoExit(false);
        return $application;
    }

    public function bootApplication(): void
    {
        IntegrationTestLogger::resetLoggedException();
        if ($this->kernel) {
            return;
        }
        $boundedContexts = $this->boundedContextConfig->toArray();
        $this->authenticationCookie = null;
        $this->kernel = new SymfonyTestingKernel(
            [
                'bounded_contexts' => $boundedContexts,
                'datalayers' => [
                    'default_datalayer' => $this->applicationConfig->getDatalayerImplementation()->name,
                ],
                'encryption_key' => 'test',
                'enable_doctrine_bundle_connection' => true,
                'enable_security' => $this->applicationConfig->doesIncludeSecurity(),
                'doctrine' => [
                    'run_migrations' => true,
                    'connection_params' => [
                        'driver' => 'pdo_sqlite'
                    ]
                ],
            ],
            $this->applicationConfig->doesIncludeTemplating(),
            $this->applicationConfig->doesIncludeSecurity(),
        );
        $this->kernel->boot();
    }

    public function getServiceContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }

    public function cleanApplication(): void
    {
        $this->kernel = null;
    }

    public function httpRequestGet(string $uri): ResponseInterface
    {
        $parameters = [];
        $parameterRaw = parse_url($uri, PHP_URL_QUERY);
        if ($parameterRaw) {
            parse_str($parameterRaw, $parameters);
        }
        $sfRequest = Request::create($uri, parameters: $parameters);
        if ($this->authenticationCookie) {
            $sfRequest->cookies->set(
                AddAuthenticationCookie::COOKIE_NAME,
                $this->authenticationCookie
            );
        }
        $sfResponse = $this->kernel->handle($sfRequest);
        return $this->handleResponse($sfResponse);
    }

    public function httpRequest(TestRequestInterface $testRequest): ResponseInterface
    {
        $psrRequest = $testRequest->getRequest();
        $factory = new HttpFoundationFactory();
        $sfRequest = $factory->createRequest($psrRequest);
        if ($this->authenticationCookie) {
            $sfRequest->cookies->set(
                AddAuthenticationCookie::COOKIE_NAME,
                $this->authenticationCookie
            );
        }

        $sfResponse = $this->kernel->handle($sfRequest);
        return $this->handleResponse($sfResponse);
    }

    private function handleResponse(Response $sfResponse): ResponseInterface
    {
        $cookie = $sfResponse->headers->getCookies(
            ResponseHeaderBag::COOKIES_ARRAY
        )[""]["/"][AddAuthenticationCookie::COOKIE_NAME] ?? null;
        if ($cookie !== null) {
            $cookie = Cookie::fromString($cookie)->getValue();
        }
        $this->authenticationCookie = $cookie;
        
        $psrFactory = new NyholmPsr17Factory();
        $factory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);
        return $factory->createResponse($sfResponse);
    }

    public function loginAs(DecryptedAuthenticatedUser $user): void
    {
        $textEncrypter = new TextEncrypter('test');
        $this->getServiceContainer()->get('apie')->find(
            $user->getId(),
            $user->getBoundedContextId()
        );
        $this->authenticationCookie = $textEncrypter->encrypt($user->toNative());
    }

    /**
     * Forget that you are logged in.
     */
    public function logout(): void
    {
        $this->authenticationCookie = null;
    }

    public function getLoggedInAs(): ?DecryptedAuthenticatedUser
    {
        if (empty($this->authenticationCookie)) {
            return null;
        }
        $textEncrypter = new TextEncrypter('test');
        return DecryptedAuthenticatedUser::fromNative(
            $textEncrypter->decrypt($this->authenticationCookie),
        );
    }
}
