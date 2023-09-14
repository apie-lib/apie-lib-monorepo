<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;

class SymfonyTestApplication implements TestApplicationInterface
{
    private ?SymfonyTestingKernel $kernel = null;

    public function __construct(
        private readonly ApplicationConfig $applicationConfig,
        private readonly BoundedContextConfig $boundedContextConfig
    ) {
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->applicationConfig;
    }

    public function bootApplication(): void
    {
        if ($this->kernel) {
            return;
        }
        $boundedContexts = $this->boundedContextConfig->toArray();
        $this->kernel = new SymfonyTestingKernel(
            [
                'bounded_contexts' => $boundedContexts,
                'datalayers' => [
                    'default_datalayer' => $this->applicationConfig->getDatalayerImplementation()->name,
                ],
                'enable_doctrine_bundle_connection' => false,
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
        $sfResponse = $this->kernel->handle(Request::create($uri));
        $psrFactory = new NyholmPsr17Factory();
        $factory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);
        return $factory->createResponse($sfResponse);
    }

    public function httpRequest(TestRequestInterface $testRequest): ResponseInterface
    {
        $psrRequest = $testRequest->getRequest();
        $factory = new HttpFoundationFactory();
        $sfRequest = $factory->createRequest($psrRequest);

        $sfResponse = $this->kernel->handle($sfRequest);
        $psrFactory = new NyholmPsr17Factory();
        $factory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);
        return $factory->createResponse($sfResponse);
    }
}
