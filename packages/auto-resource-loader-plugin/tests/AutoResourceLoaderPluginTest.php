<?php


namespace Apie\Tests\AutoResourceLoaderPlugin;

use Apie\AutoResourceLoaderPlugin\AutoResourceLoaderPlugin;
use Apie\MockObjects\ApiResources\FullRestObject;
use Apie\MockObjects\ApiResources\SimplePopo;
use Apie\MockObjects\ApiResources\SumExample;
use PHPUnit\Framework\TestCase;

class AutoResourceLoaderPluginTest extends TestCase
{
    public function testGetResources()
    {
        $testItem = new AutoResourceLoaderPlugin('Apie\\MockObjects\\ApiResources');
        $this->assertEquals(
            [
                FullRestObject::class,
                SimplePopo::class,
                SumExample::class,
            ],
            $testItem->getResources()
        );
    }
}