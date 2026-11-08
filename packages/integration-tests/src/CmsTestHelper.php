<?php
namespace Apie\IntegrationTests;

use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;

class CmsTestHelper extends IntegrationTestHelper
{
    public function createMinimalFrameworkConfig(): ApplicationConfig
    {
        return new ApplicationConfig(
            false,
            false,
            DatalayerImplementation::IN_MEMORY
        );
    }
}
