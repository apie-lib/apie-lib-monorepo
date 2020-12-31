<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\Url;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     * @dataProvider validProvider
     * @test
     */
    public function it_validates_urls(string $input)
    {
        $url = new Url($input);
        $this->assertEquals($input, $url->toNative());
    }

    public function validProvider()
    {
        yield ['https://www.example.com/'];
        yield ['http://localtest.me'];
        yield ['ftp://localhost/'];
    }

    /**
     * @dataProvider invalidProvider
     * @test
     */
    public function it_refuses_invalid_urls(string $input)
    {
        $this->expectException(InvalidValueForValueObjectException::class);
        new Url($input);
    }

    public function invalidProvider()
    {
        yield ['test.me'];
        yield [' http://www.space-before-urlpis-not-valid.nl'];
    }
}