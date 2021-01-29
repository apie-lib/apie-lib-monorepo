<?php


namespace Apie\Tests\OpenapiSchema\ValueObjects;


use Apie\OpenapiSchema\ValueObjects\UrlsWithPlaceholders;
use PHPUnit\Framework\TestCase;

class UrlWithPlaceholdersTest extends TestCase
{
    public function testFromNative()
    {
        $testItem = UrlsWithPlaceholders::fromNative('https://application-name.{host}/v{version}');
        $this->assertEquals(
            'https://application-name.{host}/v{version}',
            $testItem->toNative()
        );
        $this->assertEquals(
            ['host', 'version'],
            $testItem->getPlaceholders()
        );
        $this->assertEquals(
            ['host', 'version'],
            $testItem->getPlaceholders(),
            'Calling it again checks if cache validation works'
        );

        $this->assertEquals(
            'https://application-name.disney.com/v1.5',
            $testItem->replace(
                [
                    'version' => 1.5,
                    'host' => 'disney.com'
                ]
            )->toNative()
        );
    }

    /**
     * @test
     */
    public function it_can_not_result_in_invalid_urls_with_replacements()
    {
        $testItem = UrlsWithPlaceholders::fromNative('https://admin:{password}@{username}.app.nl');
        $this->assertEquals(
            'https://admin:12%40421%23%24@way2go.app.nl',
            $testItem->replace(
                [
                    'password' => '12@421#$',
                    'username' => 'way2go'
                ]
            )->toNative()
        );
    }
}