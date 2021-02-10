<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Spec\ExternalDocs;
use Apie\OpenapiSchema\Spec\Tag;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use PHPUnit\Framework\TestCase;

/**
 * Class TagTest
 * @package Apie\Tests\OpenapiSchema\Spec
 */
class TagTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_filled_in_with_immutability()
    {
        $testItem = new Tag('tag');
        $testItem2 = $testItem->with('description', 'This is a description');
        $this->assertNull($testItem->getDescription());
        $this->assertNull($testItem->getExternalDocs());
        $this->assertEquals('This is a description', $testItem2->getDescription());
        $testItem2 = $testItem2
            ->with('externalDocs', ['url' => 'https://www.example.nl'])
            ->with('x-extra', 'Extra field');
        $this->assertEquals(
            new ExternalDocs(new Url('https://www.example.nl')),
            $testItem2->getExternalDocs()
        );
        $this->assertEquals(
            [
                'name' => 'tag',
                'description' => 'This is a description',
                'externalDocs' => [
                    'url' => 'https://www.example.nl',
                ],
                'x-extra' => 'Extra field',
            ],
            $testItem2->toNative()
        );
    }

    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Tag::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            ['name' => 'tag-name'],
            ['name' => 'tag-name'],
        ];
        yield [
            [
                'name' => 'tag-name',
                'description' => 'tag me',
                'externalDocs' => [
                    'url' => 'https://www.example.nl',
                    'description' => 'Example doc',
                    'x-extra-doc' => 42,
                ],
                'x-extra' => 666,
            ],
            [
                'name' => 'tag-name',
                'description' => 'tag me',
                'externalDocs' => [
                    'url' => 'https://www.example.nl',
                    'description' => 'Example doc',
                    'x-extra-doc' => 42,
                ],
                'x-extra' => 666,
            ],
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Tag::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            MissingValueException::class, []
        ];
    }
}
