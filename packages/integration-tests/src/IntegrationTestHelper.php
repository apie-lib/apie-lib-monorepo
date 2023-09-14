<?php
namespace Apie\IntegrationTests;

use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Concerns\CreatesApieBoundedContext;
use Apie\IntegrationTests\Concerns\CreatesApplications;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;

final class IntegrationTestHelper
{
    use CreatesApplications;
    use CreatesApieBoundedContext;

    public function createDbLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(DoctrineEntityDatalayer::class) ? DatalayerImplementation::DB_DATALAYER : null;
    }

    public function createFakerLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(FakerDatalayer::class) ? DatalayerImplementation::FAKER : null;
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
}
