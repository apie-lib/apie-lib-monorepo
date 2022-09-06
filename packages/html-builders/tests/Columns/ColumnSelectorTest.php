<?php
namespace Apie\Tests\HtmlBuilders\Columns;

use Apie\Fixtures\Entities\Order;
use Apie\Fixtures\Entities\Polymorphic\Animal;
use Apie\Fixtures\Entities\Polymorphic\Cow;
use Apie\HtmlBuilders\Columns\ColumnSelector;
use PHPUnit\Framework\TestCase;

class ColumnSelectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_retrieve_columns_from_an_entity()
    {
        $testItem = new ColumnSelector();
        $this->assertEquals(['id', 'orderStatus', 'orderLines'], $testItem->getColumns(Order::class));
    }

    /**
     * @test
     */
    public function it_can_retrieve_columns_from_polymorphic_entity_base_class()
    {
        $testItem = new ColumnSelector();
        $this->assertEquals(['id', 'orderStatus', 'orderLines'], $testItem->getColumns(Animal::class));
    }

    /**
     * @test
     */
    public function it_can_retrieve_columns_from_polymorphic_entity_instance_class()
    {
        $testItem = new ColumnSelector();
        $this->assertEquals(['id', 'orderStatus', 'orderLines'], $testItem->getColumns(Cow::class));
    }
}