<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Operation;
use Apie\TypeJuggling\Exceptions\InvalidInputException;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Operation::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [[], []];
        $input = [
            'tags' => ['pet'],
            'summary' => 'Updates a pet in the store with form data',
            'operationId' => 'updatePetWithForm',
            'parameters' => [
                [
                    'name' => 'petId',
                    'in' => 'path',
                     'description' => 'ID of pet that needs to be updated',
                    'required' => true,
                    'schema' => [
                        'type' => 'string',
                    ]
                ]
            ],
            'requestBody' => [
                'content' => [
                    'application/x-www-form-urlencoded' => [
                        'schema' => [
                            'properties' => [
                                'name' => [
                                    'description' => 'Updated name of the pet',
                                    'type' => 'string',
                                ],
                                'status' => [
                                    'description' => 'Updated status of the pet',
                                    'type' => 'string',
                                ]
                            ],
                            'required' => ['status']
                        ]
                    ]
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'Pet updated',
                    'content' => [
                        'application/json' => [],
                        'application/xml' => [],
                    ]
                ],
                '405' => [
                    'description' => 'Method not allowed',
                    'content' => [
                        'application/json' => [],
                        'application/xml' => [],
                    ]
                ]
            ],
            'security' => [
                [
                    'petstore_auth' => ['write:pets', 'read:pets']
                ],
            ],
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Operation::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            InvalidInputException::class,
            ['deprecated' => [1, 2, 3]],
        ];
    }
}
