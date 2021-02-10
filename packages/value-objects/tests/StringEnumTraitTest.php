<?php


namespace Apie\Tests\ValueObjects;

use Apie\Tests\ValueObjects\Mocks\StringEnumTraitExample;
use Apie\Tests\ValueObjects\Mocks\StringEnumTraitExampleWithDuplicateValue;
use PHPUnit\Framework\TestCase;

class StringEnumTraitTest extends TestCase
{
    /**
     * @test
     * @dataProvider fromNativeProvider
     */
    public function it_can_convert_values_and_keys(string $expected, $input)
    {
        $actual = StringEnumTraitExample::fromNative($input);
        $this->assertEquals($expected, $actual->toNative());
    }

    public function fromNativeProvider()
    {
        yield [StringEnumTraitExample::A, 'A'];
        yield [StringEnumTraitExample::B, 'B'];
        yield [StringEnumTraitExample::A, StringEnumTraitExample::A];
        yield [StringEnumTraitExample::B, StringEnumTraitExample::B];
    }

    /**
     * @test
     * @dataProvider duplicateProvider
     */
    public function it_can_handle_duplicate_values(string $expected, $input)
    {
        $actual = StringEnumTraitExampleWithDuplicateValue::fromNative($input);
        $this->assertEquals($expected, $actual->toNative());
    }

    public function duplicateProvider()
    {
        yield [StringEnumTraitExampleWithDuplicateValue::A, 'A'];
        yield [StringEnumTraitExampleWithDuplicateValue::B, 'B'];
        yield [StringEnumTraitExampleWithDuplicateValue::C, 'C'];
        yield [StringEnumTraitExampleWithDuplicateValue::D, 'D'];
        yield [StringEnumTraitExampleWithDuplicateValue::B, StringEnumTraitExampleWithDuplicateValue::A];
    }
}
