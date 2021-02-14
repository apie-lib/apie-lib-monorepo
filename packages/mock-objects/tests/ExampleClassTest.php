<?php
namespace Apie\Tests\MockObjects;

use Apie\MockObjects\ExampleClass;
use PHPUnit\Framework\TestCase;

class ExampleClassTest extends TestCase
{
    public function testPizza()
    {
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}