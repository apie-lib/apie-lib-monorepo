<?php


namespace Apie\Tests\ValueObjects;

use Apie\Tests\ValueObjects\Mocks\StringTraitExample;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class StringTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_switch_from_and_to_native()
    {
        $object = StringTraitExample::fromNative('  12  ');
        $this->assertEquals('12', $object->toNative());
        $object = StringTraitExample::fromNative($object);
        $this->assertEquals('12', $object->toNative());
    }

    /**
     * @test
     * @dataProvider invalidInputProvider
     */
    public function it_throws_exceptions_on_invalid_input($input)
    {
        $this->expectException(InvalidValueForValueObjectException::class);
        StringTraitExample::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [''];
        yield [new StringTraitExample('   ')];
    }
}
