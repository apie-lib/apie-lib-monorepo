<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;

/**
 * @codeCoverageIgnore
 */
trait CreatesApplications
{
    public function createTestSymfonyApplication(ApplicationConfig $applicationConfig, BoundedContextConfig $boundedContextConfig): ?TestApplicationInterface
    {
        return new SymfonyTestApplication($applicationConfig, $boundedContextConfig);
    }

    public function createTestLaravelApplication(ApplicationConfig $applicationConfig, BoundedContextConfig $boundedContextConfig): ?TestApplicationInterface
    {
        // you can not disable templating in Laravel
        if (!$applicationConfig->doesIncludeTemplating()) {
            return null;
        }
        return new LaravelTestApplication($applicationConfig, $boundedContextConfig);
    }

    public function onlyLaravelApplication(TestApplicationInterface $application): ?LaravelTestApplication
    {
        return $application instanceof LaravelTestApplication ? $application : null;
    }

    public function onlySymfonyApplication(TestApplicationInterface $application): ?SymfonyTestApplication
    {
        return $application instanceof SymfonyTestApplication ? $application : null;
    }
}
