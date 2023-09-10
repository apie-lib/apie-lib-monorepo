<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;

trait CreatesApplications
{
    public function createTestSymfonyApplication(ApplicationConfig $applicationConfig, BoundedContextConfig $boundedContextConfig): ?TestApplicationInterface
    {
        return new SymfonyTestApplication($applicationConfig, $boundedContextConfig);
    }

    public function createTestLaravelApplication(ApplicationConfig $applicationConfig, BoundedContextConfig $boundedContextConfig): ?TestApplicationInterface
    {
        return new LaravelTestApplication($applicationConfig, $boundedContextConfig);
    }
}
