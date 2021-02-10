<?php


namespace Apie\Tests\OpenapiSchema\ValueObjects;

use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\ValueObjects\OpenApiVersion;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class OpenApiVersionTest extends TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValidValue(string $input)
    {
        $testItem = OpenApiVersion::fromNative($input);
        $this->assertEquals($input, $testItem->toNative());
    }

    public function validProvider()
    {
        yield [Constants::OPENAPI_VERSION];
        yield ['3.0.1'];
        yield ['3.0.0'];
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalidValue(string $input)
    {
        $this->expectException(InvalidValueForValueObjectException::class);
        OpenApiVersion::fromNative($input);
    }

    public function invalidProvider()
    {
        yield ['2.0'];
    }
}
