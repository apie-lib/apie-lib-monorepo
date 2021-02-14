<?php


namespace Apie\Tests\CommonApie;


use Apie\ApplicationInfoPlugin\ApplicationInfoPlugin;
use Apie\CommonApie\DefaultApie;
use Apie\Core\PluginInterfaces\ResourceProviderInterface;
use Apie\MockObjects\ApiResources\SimplePopo;
use PHPUnit\Framework\TestCase;

class DefaultApieTest extends TestCase
{
    /**
     * @test
     */
    public function it_works()
    {
        $plugin = new class implements ResourceProviderInterface {

            public function getResources(): array
            {
                return [SimplePopo::class];
            }
        };
        $testItem = DefaultApie::createDefaultApie(true, [$plugin], null, true);
        $this->assertInstanceOf(ApplicationInfoPlugin::class, $testItem->getPlugin(ApplicationInfoPlugin::class));
        $this->assertSame($plugin, $testItem->getPlugin(get_class($plugin)));
        $this->assertNull($testItem->getCacheFolder());
        $this->assertTrue($testItem->isDebug());
    }
}