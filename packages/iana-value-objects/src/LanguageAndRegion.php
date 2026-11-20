<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\SnowflakeIdentifier;
use Apie\IanaValueObjects\LanguageTag\Language;
use Apie\IanaValueObjects\LanguageTag\LanguageRegion;
use Faker\Generator;

#[FakeMethod('createRandom')]
#[ExampleValue('nl', 'Language only')]
#[ExampleValue('en-US', 'Language and region')]
final class LanguageAndRegion extends SnowflakeIdentifier
{
    public function __construct(private Language $language, private ?LanguageRegion $region = null)
    {
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getRegion(): ?LanguageRegion
    {
        return $this->region;
    }

    public function toPreferredValue(): static
    {
        $language = $this->language->toPreferredValue();
        $region = $this->region?->toPreferredValue();
        return new static($language, $region);
    }

    protected static function getSeparator(): string
    {
        return '-';
    }

    public static function createRandom(Generator $factory): LanguageAndRegion
    {
        return new LanguageAndRegion(
            $factory->fakeClass(Language::class),
            $factory->boolean(50) ? $factory->fakeClass(LanguageRegion::class) : null
        );
    }
}
