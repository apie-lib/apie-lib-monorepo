<?php
namespace Apie\IntegrationTests\Applications\Laravel;

use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\LaravelApie\ApieServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;
use Orchestra\Testbench\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class LaravelTestApplication extends TestCase implements TestApplicationInterface
{
    public function __construct(
        private readonly ApplicationConfig $applicationConfig,
        private readonly BoundedContextConfig $boundedContextConfig
    ) {
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->applicationConfig;
    }

    protected function defineEnvironment($app)
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set(
                'apie.bounded_contexts',
                $this->boundedContextConfig->toArray()
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
        $this->setUp();
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
        $psrFactory = new NyholmPsr17Factory();
        $factory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);
        return $factory->createResponse($laravelResponse);
    }

    protected function getPackageProviders($app)
    {
        return [ApieServiceProvider::class];
    }
}
