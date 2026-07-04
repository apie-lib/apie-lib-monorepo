<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\LanguageTag\LanguageTag;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageTagTest extends TestCase
{
    use TestWithFaker;
    #[Test]
    #[DataProvider('provideValidLanguageTags')]
    public function it_allows_valid_inputs(string $expected, string $input): void
    {
        $testItem = new LanguageTag($input);
        $this->assertSame($expected, $testItem->toNative());
    }

    #[Test]
    #[DataProvider('provideValidLanguageTags')]
    public function it_allows_valid_inputs_from_native(string $expected, string $input): void
    {
        $testItem = LanguageTag::fromNative($input);
        $this->assertSame($expected, $testItem->toNative());
    }


    public static function provideValidLanguageTags(): array
    {
        return [
            ['en', 'en'],
            ['en-US', 'en-US'],
            ['zh-Hant', 'zh-Hant'],
            ['zh-Hant-TW', 'zh-Hant-TW'],
            ['sl-rozaj-biske-1994', 'sl-rozaj-biske-1994'],
            ['de-CH-1901', 'de-CH-1901'],
            ['sr-Latn-RS', 'sr-Latn-RS'],
            ['es-419', 'es-419'],
            ['de-t-en', 'de-t-en'],
            ['en-a-bbb-x-ccc', 'en-a-bbb-x-ccc'],
            ['en-Latn-US', 'en-Latn-US'],
            ['en-Latn-US-u-ca-islamic', 'en-Latn-US-u-ca-islamic'],
            ['zh-Hans-CN', 'zh-Hans-CN'],
            ['zh-Hans-CN-u-ca-chinese', 'zh-Hans-CN-u-ca-chinese'],
        ];
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(LanguageTag::class, interval: 10);
    }
}