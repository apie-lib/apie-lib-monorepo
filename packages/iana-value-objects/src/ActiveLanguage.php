<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All language tags registered in the IANA Language Subtag Registry.
 *
 * This one only contains the main languages, so no regions like "en-US" or "zh-yue" are included.
 * @see https://www.iana.org/assignments/language-subtag-registry
 *
 * Only active languages can be used.
 */
final class ActiveLanguage implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/languages.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
