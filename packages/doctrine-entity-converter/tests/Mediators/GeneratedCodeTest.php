<?php
namespace Apie\Tests\DoctrineEntityConverter\Mediators;

use Apie\DoctrineEntityConverter\Mediators\GeneratedCode;
use Apie\Fixtures\Dto\EmptyDto;
use PHPUnit\Framework\TestCase;

class GeneratedCodeTest extends TestCase
{
    public function testCodeGeneration() 
    {
        $testItem = new GeneratedCode('Example', EmptyDto::class);
        $this->assertEquals(file_get_contents(__DIR__ . '/../../fixtures/Example.php'), $testItem->toCode());
    }
}