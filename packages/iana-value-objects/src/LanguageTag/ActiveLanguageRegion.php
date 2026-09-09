<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All language regions registered in the IANA Language Subtag Registry.
 *
 * Only active language regions can be used.
 */
final class ActiveLanguageRegion implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    private static StringSet $activeOptions;

    public static function getOptions(): StringSet
    {
        if (!isset(static::$activeOptions)) {
            static::$activeOptions = new StringSet(array_map('strval', array_keys(static::getActiveData())));
        }
        return static::$activeOptions;
    }

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-regions.php';
    }
}
