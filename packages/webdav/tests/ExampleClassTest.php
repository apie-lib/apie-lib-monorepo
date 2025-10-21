<?php
namespace Apie\Tests\Webdav;

use Apie\Webdav\ExampleClass;
use PHPUnit\Framework\TestCase;

class ExampleClassTest extends TestCase
{
    public function testPizza()
    {
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}
