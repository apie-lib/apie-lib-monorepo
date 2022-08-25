<?php
namespace Apie\Tests\CmsLayoutGraphite;

use Apie\CmsLayoutGraphite\ExampleClass;
use PHPUnit\Framework\TestCase;

class ExampleClassTest extends TestCase
{
    public function testPizza()
    {
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}