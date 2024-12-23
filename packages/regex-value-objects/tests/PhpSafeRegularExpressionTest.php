<?php
namespace Apie\Tests\RegexValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\RegexValueObjects\Exceptions\ExpressionContainsLookAheads;
use Apie\RegexValueObjects\Exceptions\ExpressionContainsRepeatsInRepeats;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;
use Apie\RegexValueObjects\PhpSafeRegularExpression;
use PHPUnit\Framework\TestCase;

class PhpSafeRegularExpressionTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_valid_regular_expressions(string $expected, string $expectedDelimiter, string $expectedModifier, string $input)
    {
        $testItem = new PhpSafeRegularExpression($input);
        $this->assertEquals($expectedDelimiter, $testItem->getDelimiter());
        $this->assertEquals($expectedModifier, $testItem->getModifiers());
        $this->assertEquals($expected, $testItem->toNative());
    }

    public static function inputProvider()
    {
        yield 'regular expression with modifier' => ['/test/i', '/', 'i', '/test/i'];
        yield 'regular expression without modifier' => ['/test/', '/', '', '/test/'];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions(string $expectedClass, string $input)
    {
        $this->expectException($expectedClass);
        new PhpSafeRegularExpression($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions_with_fromNative(string $expectedClass, string $input)
    {
        $this->expectException($expectedClass);
        PhpSafeRegularExpression::fromNative($input);
    }

    public static function invalidProvider()
    {
        yield 'empty string' => [InvalidPhpRegularExpression::class, ''];
        //yield ['[a-z]'];
        yield 'missing ending delimiter' => [InvalidPhpRegularExpression::class, "/[a-z]"];
        yield 'unknown modifier' => [InvalidPhpRegularExpression::class, '/[a-z]/0'];
        yield 'contains look aheads' => [ExpressionContainsLookAheads::class, '/^[a-z]+(?=\d)/'];
        yield '2 repeats after each other' => [InvalidPhpRegularExpression::class, '/^[a-z]+{2,}/'];
        yield 'contains look ahead at end of string' => [ExpressionContainsLookAheads::class, '/[a-z]+(?<!\d)/'];
        yield 'contains look ahead at start of string' => [ExpressionContainsLookAheads::class, '/(?<!\d)[a-z]+/'];
        yield 'repeat in repeat' => [ExpressionContainsRepeatsInRepeats::class, '/^([a-z]{3,}){2,}$/'];
        yield '+ inside * repeat' => [ExpressionContainsRepeatsInRepeats::class, '/([a-z]+)*/'];
        yield 'contains look aheads and repeat in repeat' => [ExpressionContainsLookAheads::class, '/^\d+(?:,\d+)+$/'];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            PhpSafeRegularExpression::class,
            'PhpSafeRegularExpression-post',
            [
                'type' => 'string',
                'format' => 'phpsaferegularexpression'
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PhpSafeRegularExpression::class);
    }
}
