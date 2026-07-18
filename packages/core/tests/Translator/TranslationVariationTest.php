<?php
namespace Apie\Tests\Core\Translator;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Translator\Enums\Pluralization;
use Apie\Core\Translator\ValueObjects\TranslationVariation;
use PHPUnit\Framework\TestCase;
use Generator;

class TranslationVariationTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('translationVariationProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_translation_variation_from_a_translation_string(
        TranslationVariation $expected,
        string $translationString
    ) {
        $actual = TranslationVariation::createFromTranslation($translationString);

        $this->assertEquals($expected, $actual);
    }

    public static function translationVariationProvider(): Generator
    {
        yield 'simple translation' => [
            new TranslationVariation(),
            'apie.resource.order',
        ];

        yield 'bounded plural authenticated' => [
            new TranslationVariation(
                new BoundedContextId('example'),
                Pluralization::Plural,
                true
            ),
            'apie.bounded.example.resource.order.plural.authenticated',
        ];

        yield 'plural unauthenticated' => [
            new TranslationVariation(
                null,
                Pluralization::Plural,
                false
            ),
            'apie.resource.order.plural.unauthenticated',
        ];

        yield 'singular authenticated' => [
            new TranslationVariation(
                null,
                Pluralization::Singular,
                true
            ),
            'apie.resource.order.singular.authenticated',
        ];

        yield 'bounded singular unauthenticated' => [
            new TranslationVariation(
                new BoundedContextId('test'),
                Pluralization::Singular,
                false
            ),
            'apie.bounded.test.resource.order.singular.unauthenticated',
        ];
    }
}
