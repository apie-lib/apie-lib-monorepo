<?php

namespace Apie\Tests\TypeJuggling;

use Apie\Tests\CompositeValueObjects\Mocks\ExampleWithMixedTypehint;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\TypeJuggling\FloatingPoint;
use Apie\TypeJuggling\Integer;
use Apie\TypeJuggling\NullObject;
use PHPUnit\Framework\TestCase;

class CompoundTest extends TestCase
{
    /**
     * @dataProvider fromNativeProvider
     */
    public function testFromNative($expected, $input)
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $actual = $testItem->fromNative($input);
        $this->assertEquals($expected, $actual);
    }

    public function fromNativeProvider()
    {
        yield [0, false];
        yield [1, true];
        yield [0, 0];
        yield [1.5, 1.5];
        yield [0, '0'];
    }

    /**
     * @dataProvider supportsFromNativeProvider
     */
    public function testSupportsFromNative($expected, $input)
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $this->assertEquals($expected, $testItem->supportsFromNative($input));
        $this->assertEquals($expected, $testItem->supportsToNative($input));
    }

    public function supportsFromNativeProvider()
    {
        yield [false, false];
        yield [false, true];
        yield [true, null];
        yield [false, ''];
        yield [false, 'false'];
        yield [true, 0];
        yield [false, '0'];
        yield [true, 1.5];
        yield [false, []];
        yield [false, ['g' => 1]];
        yield [false, new ExampleWithMixedTypehint(false)];
    }

    /**
     * @dataProvider toNativeProvider
     */
    public function testToNative($expected, $input)
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $this->assertEquals($expected, $testItem->toNative($input));
    }

    public function toNativeProvider()
    {
        yield [0, false];
        yield [1, true];
        yield [null, null];
        yield [0, 0];
        yield [1.5, 1.5];
        yield [0, '0'];
    }

    /**
     * @dataProvider supportsProvider
     */
    public function testSupports($expected, $input)
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $this->assertEquals($expected, $testItem->supports($input));
    }

    public function supportsProvider()
    {
        yield [true, false];
        yield [true, true];
        yield [true, null];
        yield [false, ''];
        yield [false, 'false'];
        yield [true, 0];
        yield [true, '0'];
        yield [true, 1.5];
        yield [true, '1.5'];
        yield [false, []];
        yield [false, ['g' => 1]];
        yield [false, new ExampleWithMixedTypehint(false)];
    }

    public function testFromMissingValue_with_match()
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $this->assertNull($testItem->fromMissingValue());
    }

    public function testFromMissingValue_no_match()
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new Integer('fieldName'));
        $this->expectException(MissingValueException::class);
        $testItem->fromMissingValue();
    }

    public function testToString()
    {
        $testItem = new Compound('fieldName', new FloatingPoint('fieldName'), new NullObject());
        $this->assertEquals('(float|null)', $testItem->__toString());
    }
}
