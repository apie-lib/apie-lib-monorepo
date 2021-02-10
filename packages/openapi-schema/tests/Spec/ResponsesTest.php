<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\OpenapiSchema\Spec\Responses;
use PHPUnit\Framework\TestCase;

class ResponsesTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Responses::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'default' => [
                'description' => 'default response',
            ],
        ];
        yield [
            $input, $input
        ];
        $input = [
            'default' => [
                'description' => 'default response',
            ],
            'x-extra-description' => 'This is not even an array but still an extension',
        ];
        yield [
            $input, $input
        ];
        $input = [
            'default' => [
                'description' => 'default response',
            ],
            '200' => [
                'description' => 'Ok status',
            ],
            '401' => [
                '$ref' => '#/components/responses/access-denied',
            ],
            '403' => [
                'description' => 'Access denied',
            ]
        ];
        yield [
            $input, $input
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Responses::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            IgnoredKeysException::class,
            [
                'default' => 'this is not an array',
            ]
        ];
        yield [
            IgnoredKeysException::class,
            [
                '404' => 'this is not an array',
            ]
        ];
    }
}
