<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\OpenapiSchema\Spec\Response;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Response::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'description' => 'This is a description of a response',
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Response::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            MissingValueException::class,
            []
        ];
        yield [
            IgnoredKeysException::class,
            [
                'not-there' => 1,
            ]
        ];
    }
}
