<?php


namespace Apie\Tests\StaticResourcesPlugin;


use Apie\MockObjects\ApiResources\SimplePopo;
use Apie\StaticResourcesPlugin\StaticResourcesPlugin;
use PHPUnit\Framework\TestCase;

class StaticResourcesPluginTest extends TestCase
{
    /**
     * @test
     */
    public function it_works()
    {
        $testItem = new StaticResourcesPlugin([SimplePopo::class]);
        $this->assertEquals([SimplePopo::class], $testItem->getResources());
    }
}