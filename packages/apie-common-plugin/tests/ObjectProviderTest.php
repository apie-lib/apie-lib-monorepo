<?php
namespace Apie\Tests\ApieCommonPlugin;

use Apie\ApieCommonPlugin\ObjectProvider;
use Apie\Core\Lists\ItemList;
use Apie\Fixtures\Entities\Order;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use PHPUnit\Framework\TestCase;

class ObjectProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_filter_objects()
    {
        $testItem = new class extends ObjectProvider {
            protected const DEFINED_CLASSES = [
                AddressWithZipcodeCheck::class,
                Order::class,
                ItemList::class,
            ];
        };
        $this->assertEquals([AddressWithZipcodeCheck::class], $testItem->getAvailableValueObjects());
        $this->assertEquals([ItemList::class], $testItem->getAvailableLists());
        $this->assertEquals([], $testItem->getAvailableHashmaps());
        $this->assertEquals([], $testItem->getAvailableDtos());
    }
}
