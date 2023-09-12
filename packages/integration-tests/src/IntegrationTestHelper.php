<?php
namespace Apie\IntegrationTests;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Concerns\CreatesApplications;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;

final class IntegrationTestHelper
{
    use CreatesApplications;

    public function createDbLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(DoctrineEntityDatalayer::class) ? DatalayerImplementation::DB_DATALAYER : null;
        ;
    }

    public function createFakerLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(FakerDatalayer::class) ? DatalayerImplementation::FAKER : null;
        ;
    }

    public function createInMemoryLayerImplementation(): DatalayerImplementation
    {
        return DatalayerImplementation::IN_MEMORY;
    }

    public function createFullFrameworkConfig(DatalayerImplementation $datalayerImplementation): ApplicationConfig
    {
        return new ApplicationConfig(
            true,
            true,
            $datalayerImplementation
        );
    }

    public function createMinimalFrameworkConfig(): ApplicationConfig
    {
        return new ApplicationConfig(
            false,
            false,
            DatalayerImplementation::IN_MEMORY
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
