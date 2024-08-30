<?php

namespace Apie\IntegrationTests;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;

class FixtureUtils
{
    public static function getOpenapiFixtureFile(TestApplicationInterface $testApplication, bool $json = true): string
    {
        return __DIR__
            . '/../fixtures/RestApi/openapi'
            . $testApplication->getApplicationConfig()->getDatalayerImplementation()->getShortName()
            . ($json ? '.json' : '.yaml');
    }
}
