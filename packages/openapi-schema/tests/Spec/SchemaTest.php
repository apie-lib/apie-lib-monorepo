<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\OpenapiSchema\Spec\Schema;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class SchemaTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Schema::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        foreach (Finder::create()->files()->in(__DIR__ . '/../fixtures/schemas') as $file) {
            $input = json_decode(file_get_contents($file), true);
            yield [$input, $input, $file->getBasename('.json')];
        }
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Schema::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            IgnoredKeysException::class,
            [
                'type' => 'array',
                'format' => 'uri'
            ]
        ];
        yield [
            InvalidValueForValueObjectException::class,
            [
                'type' => ['string', 'number']
            ]
        ];
    }
}
