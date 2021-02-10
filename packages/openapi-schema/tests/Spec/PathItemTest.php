<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\DuplicateParameterInParameterList;
use Apie\OpenapiSchema\Spec\PathItem;
use PHPUnit\Framework\TestCase;

class PathItemTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = PathItem::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [[], []];
        $input = [
            'get' => [
                'description' => 'Returns pets based on ID',
                'summary' => 'Find pets by ID',
                'operationId' => 'getPetsById',
                'responses' => [
                    '200' => [
                        'description' => 'pet response',
                        'content' => [
                            '*/*' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Pet',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'default' => [
                        'description' => 'error payload',
                        'content' => [
                            'text/html' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/ErrorModel',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'parameters' => [
                [
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
                ]
            ]
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        PathItem::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            DuplicateParameterInParameterList::class,
            [
                'parameters' => [
                    ['name' => 'key', 'in' => 'header'],
                    ['name' => 'key', 'in' => 'header', 'description' => 'duplicate example']
                ]
            ]
        ];
    }
}
