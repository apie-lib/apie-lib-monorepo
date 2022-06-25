<?php
namespace Apie\Tests\CommonValueObjects\Regexes;

use Apie\CommonValueObjects\Exceptions\InvalidPhpRegularExpression;
use Apie\CommonValueObjects\Regexes\PhpRegularExpression;
use PHPUnit\Framework\TestCase;

class PhpRegularExpressionTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_valid_regular_expressions(string $expected, string $input)
    {
        $testItem = new PhpRegularExpression($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['/test/i', '/test/i'];
        yield ['/test/', '/test/'];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        new PhpRegularExpression($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions_with_fromNative(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        PhpRegularExpression::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [''];
        //yield ['[a-z]'];
        yield ["/[a-z]"];
        yield ['/[a-z]/0'];
    }
}
