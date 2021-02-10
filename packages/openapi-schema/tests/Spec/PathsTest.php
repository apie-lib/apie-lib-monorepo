<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Paths;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class PathsTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Paths::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            '/me' => ["get" => []],
            'x-extra' => 12,
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Paths::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            InvalidValueForValueObjectException::class,
            []
        ];
        yield [
            InvalidValueForValueObjectException::class,
            [
                'x-extra' => 12,
            ]
        ];
    }
}
