<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Parameter;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Parameter::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'name' => 'id',
            'in' => 'path',
            'description' => 'ID of pet to use',
            'required' => true,
            'schema' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string'
                ]
            ],
            'style' => 'simple',
        ];
        yield [$input, $input];
        $input = [
            'name' => 'username',
            'in' => 'path',
            'description' => 'username to fetch',
            'required' => true,
            'schema' => [
                'type' => 'string',
            ]
        ];
        yield [$input, $input];
        $input = [
            'name' => 'session',
            'in' => 'cookie',
            'description' => 'session id',
            'required' => true,
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Parameter::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            MissingValueException::class,
            []
        ];
    }
}
