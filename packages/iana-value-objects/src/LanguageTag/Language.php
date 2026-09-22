<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All languages registered in the IANA Language Subtag Registry.
 *
 * This one only contains the main languages, so no subtags like "en-US" or "zh-yue" are included.
 * For those, use the LanguageSubtag value object.
 *
 * @see https://www.iana.org/assignments/language-subtag-registry
 *
 * Any language that is not active anymore can be used as well (for data integrity).
 */
#[ExampleValue('nl')]
#[ExampleValue('en')]
final class Language implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/languages.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
