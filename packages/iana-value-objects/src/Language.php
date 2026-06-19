<?php
namespace Apie\IanaValueObjects;

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
final class Language implements StringValueObjectInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/languages.php';
    }
}