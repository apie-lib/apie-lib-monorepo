<?php
namespace Apie\Tests\Maker\ValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\ValueObjects\ClassNameReference;
use Apie\Maker\ValueObjects\VendorValueObject;
use PHPUnit\Framework\TestCase;

class ClassNameReferenceTest extends TestCase
{
    use TestWithFaker;
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_constructor()
    {
        $testItem = new ClassNameReference(VendorValueObject::class);
        $this->assertEquals(VendorValueObject::class, $testItem->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_fromNative()
    {
        $testItem = ClassNameReference::fromNative(VendorValueObject::class);
        $this->assertEquals(VendorValueObject::class, $testItem->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_faker()
    {
        $this->runFakerTest(ClassNameReference::class);
    }
}
