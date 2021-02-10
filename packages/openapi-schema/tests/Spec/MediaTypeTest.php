<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\ExampleAndExamplesAreMutuallyExclusive;
use Apie\OpenapiSchema\Spec\MediaType;
use PHPUnit\Framework\TestCase;

class MediaTypeTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = MediaType::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [[], []];
        $input = [
            'schema' => [
                '$ref' => '#/components/schemas/Pet',
            ],
            'examples' => [
                'cat' => [
                    'summary' => 'An example of a cat',
                    'value' => [
                        'name' => 'Fluffy',
                        'petType' => 'Cat',
                        'color' => 'White',
                        'gender' => 'male',
                        'breed' => 'Persian',
                    ]
                ],
                'dog' => [
                    'summary' => 'An example of a dog with a cat\'s name',
                    'value' => [
                        'name' => 'Puma',
                        'petType' => 'Dog',
                        'color' => 'Black',
                        'gender' => 'female',
                        'breed' => 'Mixed',
                    ]
                ],
                'frog' => [
                    '$ref' => '#/components/examples/frog-example',
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
        MediaType::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            ExampleAndExamplesAreMutuallyExclusive::class,
            [
                'example' => 42,
                'examples' => [
                    'example-1' => [
                        'value' => 42,
                    ]
                ]
            ]
        ];
    }
}
