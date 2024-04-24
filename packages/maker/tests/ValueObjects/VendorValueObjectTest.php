<?php
namespace Apie\Tests\Maker\ValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\ValueObjects\VendorValueObject;
use PHPUnit\Framework\TestCase;

class VendorValueObjectTest extends TestCase
{
    use TestWithFaker;
    /**
     * @test
     */
    public function it_works_with_constructor()
    {
        $testItem = new VendorValueObject(VendorValueObject::class);
        $this->assertEquals(VendorValueObject::class, $testItem->toNative());
    }

    /**
     * @test
     */
    public function it_works_with_fromNative()
    {
        $testItem = VendorValueObject::fromNative(VendorValueObject::class);
        $this->assertEquals(VendorValueObject::class, $testItem->toNative());
    }

    /**
     * @test
     */
    public function it_works_with_faker()
    {
        $this->runFakerTest(VendorValueObject::class);
    }
}
