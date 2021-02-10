<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\ValueObjects\StringHashmap;
use Apie\OpenapiSchema\Exceptions\InvalidReferenceValue;
use Apie\OpenapiSchema\Spec\Reference;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;
use PHPUnit\Framework\TestCase;

class ReferenceTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Reference::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            ['$ref' => '#/components/schemas/ExampleSchema'],
            ['$ref' => '#/components/schemas/ExampleSchema', 'ignored' => true, 'x-no-extension' => true],
        ];
        yield [
            ['$ref' => '#/components/schemas/ExampleSchema'],
            new Reference('#/components/schemas/ExampleSchema'),
        ];
        yield [
            ['$ref' => '#/components/schemas/ExampleSchema'],
            StringHashmap::fromNative(['$ref' => '#/components/schemas/ExampleSchema']),
        ];
        $stringValueObject = new class('#/components/schemas/ExampleSchema') implements ValueObjectInterface {
            use StringTrait;

            protected function validValue(string $value): bool
            {
                return !empty($value);
            }

            protected function sanitizeValue(string $value): string
            {
                return trim($value);
            }
        };

        yield [
            ['$ref' => '#/components/schemas/ExampleSchema'],
            ['$ref' => $stringValueObject],
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedException, $input)
    {
        $this->expectException($expectedException);
        Reference::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            InvalidValueForValueObjectException::class, '#/components/schemas/ExampleSchema'
        ];
        yield [
            InvalidValueForValueObjectException::class, 2
        ];
        yield [
            InvalidValueForValueObjectException::class, $this
        ];
        yield [
            MissingValueException::class, []
        ];
        yield [
            InvalidReferenceValue::class, ['$ref' => 42]
        ];
        yield [
            InvalidReferenceValue::class, ['$ref' => 'pizza']
        ];
        yield [
            InvalidReferenceValue::class, ['$ref' => 'pizza']
        ];
        yield [
            InvalidReferenceValue::class, StringHashmap::fromNative(['$ref' => 'pizza'])
        ];
    }
}
