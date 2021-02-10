<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\RequestBody;
use PHPUnit\Framework\TestCase;

class RequestBodyTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = RequestBody::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'description' => 'user to add to the system',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/User',
                    ],
                    'examples' => [
                        'user' => [
                            'summary' => 'User Example',
                            'externalValue' => 'http://foo.bar/example/user-example.json',
                        ],
                    ],
                ],
                'application/xml' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/User',
                    ],
                    'examples' => [
                        'user' => [
                            'summary' => 'User Example in XML',
                            'externalValue' => 'http://foo.bar/example/user-example.xml',
                        ]
                    ]
                ],
                'text/plain' => [
                    'examples' => [
                        'user' => [
                            'summary' => 'User example in text plain format',
                            'externalValue' => 'http://foo.bar/examples/user-example.txt',
                        ]
                    ]
                ],
                '*/*' => [
                    'examples' => [
                        'user' => [
                            'summary' => 'User example in other format',
                            'externalValue' => 'http://foo.bar/examples/user-example.whatever',
                        ]
                    ]
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
        RequestBody::fromNative($input);
    }

    public function invalidInputProvider()
    {
        // TODO
    }
}
