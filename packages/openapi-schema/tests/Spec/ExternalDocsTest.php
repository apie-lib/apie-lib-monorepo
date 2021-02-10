<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\OpenapiSchema\Spec\ExternalDocs;
use PHPUnit\Framework\TestCase;

class ExternalDocsTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = ExternalDocs::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'url' => 'https://www.example.nl',
        ];
        yield [$input, $input];
        $input = [
            'url' => 'https://www.example.nl',
            'x-extension' => ['This has a spec extension'],
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        ExternalDocs::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            IgnoredKeysException::class,
            ['url' => 'https://www.earl.com', 'this-key-is-not-defined'=> 1],
        ];
    }
}
