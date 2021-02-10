<?php


namespace Apie\Tests\OpenapiSchema\ValueObjects;

use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use PHPUnit\Framework\TestCase;

class SpecificationExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_always_prefix_them_with_x()
    {
        $testItem = new SpecificationExtension(['a' => 12]);
        $this->assertEquals(
            [
                'x-a' => 12,
            ],
            $testItem->toNative()
        );
    }

    /**
     * @test
     */
    public function it_is_case_insensitive()
    {
        $testItem = new SpecificationExtension(['a' => 12, 'A' => 42]);
        $this->assertEquals(
            [
                'x-a' => 42,
            ],
            $testItem->toNative()
        );
    }

    /**
     * @test
     */
    public function it_is_immutable()
    {
        $testItem = new SpecificationExtension(['x-a' => 12]);
        $testItem2 = $testItem->withField('pizza', 'salami');
        $this->assertNotSame($testItem, $testItem2);
        $this->assertEquals(
            [
                'x-a' => 12,
            ],
            $testItem->toNative()
        );
        $this->assertEquals(
            [
                'x-a' => 12,
                'x-pizza' => 'salami',
            ],
            $testItem2->toNative()
        );
    }
}
