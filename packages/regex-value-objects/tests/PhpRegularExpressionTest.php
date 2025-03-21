<?php
namespace Apie\Tests\RegexValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;
use Apie\RegexValueObjects\PhpRegularExpression;
use PHPUnit\Framework\TestCase;

class PhpRegularExpressionTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[\PHPUnit\Framework\Attributes\DataProvider('inputProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_valid_regular_expressions(string $expected, string $expectedDelimiter, string $expectedModifier, string $input)
    {
        $testItem = new PhpRegularExpression($input);
        $this->assertEquals($expectedDelimiter, $testItem->getDelimiter());
        $this->assertEquals($expectedModifier, $testItem->getModifiers());
        $this->assertEquals($expected, $testItem->toNative());
    }

    public static function inputProvider()
    {
        yield 'regular expression with modifier' => ['/test/i', '/', 'i', '/test/i'];
        yield 'regular expression without modifier' => ['/test/', '/', '', '/test/'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_refuses_invalid_regular_expressions(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        new PhpRegularExpression($input);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_refuses_invalid_regular_expressions_with_fromNative(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        PhpRegularExpression::fromNative($input);
    }

    public static function invalidProvider()
    {
        yield 'empty string' => [''];
        //yield ['[a-z]'];
        yield 'missing ending delimiter' => ["/[a-z]"];
        yield 'unknown modifier' => ['/[a-z]/0'];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            PhpRegularExpression::class,
            'PhpRegularExpression-post',
            [
                'type' => 'string',
                'format' => 'phpregularexpression'
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PhpRegularExpression::class);
    }
}
