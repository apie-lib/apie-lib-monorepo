<?php
namespace Apie\Tests\ApieCommonPlugin;

use Apie\ApieCommonPlugin\AvailableApieObjectProvider;
use Apie\ApieCommonPlugin\ObjectProvider;
use Apie\Core\Lists\ItemList;
use Apie\Fixtures\Entities\Order;
use Apie\Fixtures\Entities\Polymorphic\Animal;
use Apie\Fixtures\Entities\Polymorphic\Cow;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ObjectProviderTest extends TestCase
{
    /**
     * @param class-string<object> $definedClass
     * @test
     * @dataProvider provideClasses
     */
    public function it_registers_existing_classes(string $definedClass)
    {
        if (!class_exists(AvailableApieObjectProvider::class)) {
            $this->markTestSkipped('Class could not be found. Did the plugin run?');
        }
        $this->assertTrue(class_exists($definedClass), $definedClass . ' class exists!');
        $this->assertEquals(
            $definedClass,
            (new ReflectionClass($definedClass))->getName(),
            'class case sensitivity is correct'
        );
    }

    public function provideClasses(): Generator
    {
        if (!class_exists(AvailableApieObjectProvider::class)) {
            $this->markTestSkipped('Class could not be found. Did the plugin run?');
        }
        $refl = new ReflectionClass(AvailableApieObjectProvider::class);
        foreach ($refl->getConstant('DEFINED_CLASSES') as $definedClass) {
            yield $definedClass => [$definedClass];
        }
    }

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
                'DoesNotExist',
                Cow::class,
            ];
        };
        $this->assertEquals([AddressWithZipcodeCheck::class], $testItem->getAvailableValueObjects());
        $this->assertEquals([ItemList::class], $testItem->getAvailableLists());
        $this->assertEquals([], $testItem->getAvailableHashmaps());
        $this->assertEquals([], $testItem->getAvailableDtos());
    }
}
