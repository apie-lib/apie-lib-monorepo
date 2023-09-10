<?php
namespace Apie\IntegrationTests;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Concerns\CreatesApplications;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;

final class IntegrationTestHelper
{
    use CreatesApplications;

    public function createFullFrameworkCOnfig(): ApplicationConfig
    {
        return new ApplicationConfig(
            true,
            true,
            DatalayerImplementation::DB_DATALAYER
        );
    }

    public function createMinimalFrameworkConfig(): ApplicationConfig
    {
        return new ApplicationConfig(
            false,
            false,
            DatalayerImplementation::DB_DATALAYER
        );
    }

    public function createExampleBoundedContext(): BoundedContextConfig
    {
        $result = new BoundedContextConfig();
        $result->addEntityNamespace(
            new BoundedContextId('types'),
            __DIR__ . '/Apie/TypeDemo',
            new EntityNamespace('Apie\\IntegrationTests\\Apie\\TypeDemo\\')
        );

        return $result;
    }
}
