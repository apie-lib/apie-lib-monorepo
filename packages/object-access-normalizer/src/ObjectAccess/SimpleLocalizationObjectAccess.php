<?php


namespace Apie\ObjectAccessNormalizer\ObjectAccess;


use Apie\ObjectAccessNormalizer\Interfaces\LocalizationAwareInterface;

class SimpleLocalizationObjectAccess extends LocalizationAwareObjectAccess
{
    public function __construct(
        LocalizationAwareInterface $localizationAware,
        bool $publicOnly = true,
        bool $disabledConstructor = false
    ) {
        $conversionFn = static function ($locale) {
            return $locale === null ? 'en' : ((string) $locale);
        };
        parent::__construct($localizationAware, $conversionFn, $publicOnly, $disabledConstructor);
    }
}
