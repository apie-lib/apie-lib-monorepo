<?php
namespace Apie\Tests\AiInstructor;

use Apie\AiInstructor\ExampleClass;
use PHPUnit\Framework\TestCase;

class ExampleClassTest extends TestCase
{
    public function testPizza()
    {
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}
