<?php
namespace Apie\CommonValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Generator;
use PHPUnit\Framework\TestCase;

class SafeHtmlTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            SafeHtml::class,
            'SafeHtml-post',
            [
                'type' => 'string',
                'format' => 'safehtml',
            ]
        );
    }

    /**
     * @test
     */
    public function it_refuses_large_html()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new SafeHtml(str_repeat('aaa', 20 * 1024 * 1024));
    }

    /**
     * @test
     * @dataProvider invalidHTMLProvider
     */
    public function it_sanitizes_html(string $expected, string $input)
    {
        $testItem = new SafeHtml($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function invalidHTMLProvider(): Generator
    {
        yield 'malformed HTML' => [
            'this is a test<div>hi</div>',
            'this is a test<div>hi'
        ];
        yield 'script tag' => [
            'test',
            '<script>alert("hi");</script>test'
        ];
        yield 'img onerror XSS' => [
            '<img />',
            '<img src="error" onerror="alert(\'hi\');" />'
        ];
        yield 'external urls open in new window' => [
            '<a href="https://www.google.com" rel="noopener noreferrer" target="_blank">google.com</a>',
            '<a href="https://www.google.com">google.com</a>',
        ];
        yield 'script + text truncating' => [
            '',
            '<script>alert("HI");//' . str_repeat('a', 70000) . PHP_EOL . '</script>'
        ];
        yield 'underline text' => [
            '<span style="text-decoration-line:underline">Underline text</span>',
            '<span style="text-decoration-line:  underline;">Underline text</span>'
        ];
        yield 'striked text' => [
            '<span style="text-decoration-line:line-through">striked text</span>',
            '<span style="text-decoration-line:  line-through;">striked text</span>',
        ];
        yield 'colored text' => [
            '<span style="background-color: #424242;color: #111">striked text</span>',
            '<span style="background-color: #424242; color: #111">striked text</span>',
        ];
        yield 'unknown style' => [
            '<span>test</span>',
            '<span style="border: solid 1px">test</span>',
        ];
    }

    /**
     * @test
     */
    public function it_indexes_html()
    {
        $testItem = new SafeHtml('<div>Test</div><p>Paragraph</p><h1>Header is important, right?</h1>');
        $this->assertEquals(
            [
                'test' => 1,
                'paragraph' => 1,
                'header' => 1,
                'is' => 1,
                'important' => 1,
                'right' => 1,
            ],
            $testItem->provideIndexes()
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(SafeHtml::class);
    }
}
