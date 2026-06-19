<?php
namespace Apie\IanaValueObjects;

use Apie\Core\ValueObjects\SnowflakeIdentifier;

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
}
