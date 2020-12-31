<?php


namespace Apie\Tests\CommonValueObjects;


use Apie\CommonValueObjects\JavascriptRegularExpression;
use Apie\CommonValueObjects\Url;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class JavascriptRegularExpressionTest extends TestCase
{
    /**
     * @dataProvider validProvider
     * @test
     */
    public function it_validates_regular_expressions(string $input)
    {
        $url = new JavascriptRegularExpression($input);
        $this->assertEquals($input, $url->toNative());
    }

    public function validProvider()
    {
        yield ['[a-z]+'];
        yield ['\d{4}\s*[a-z]{2}'];
        yield ['a sentence is also a regular expresison'];
        yield ['-z]'];
        yield ['2/3'];
    }

    /**
     * @dataProvider invalidProvider
     * @test
     */
    public function it_refuses_invalid_expressions(string $input)
    {
        $this->expectException(InvalidValueForValueObjectException::class);
        new JavascriptRegularExpression($input);
    }

    public function invalidProvider()
    {
        yield ['[a-'];
        yield ['\1 does not exist!'];
    }
}