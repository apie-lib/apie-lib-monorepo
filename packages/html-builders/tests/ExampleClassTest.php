<?php
namespace Apie\Tests\HtmlBuilders;

use Apie\HtmlBuilders\ExampleClass;
use PHPUnit\Framework\TestCase;

class ExampleClassTest extends TestCase
{
    public function testPizza()
    {
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}