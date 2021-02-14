<?php

namespace Apie\Tests\StaticConfigPlugin;

use Apie\Core\Apie;
use Apie\StaticConfigPlugin\StaticConfigPlugin;
use PHPUnit\Framework\TestCase;

class StaticConfigPluginTest extends TestCase
{
    /**
     * @var Apie
     */
    private $apie;

    protected function setUp(): void
    {
        $this->apie = new Apie([new StaticConfigPlugin('http://google-api.nu/')], true, null);
    }

    public function testGetBaseUrl()
    {
        $this->assertEquals('http://google-api.nu/', $this->apie->getBaseUrl());
    }
}
