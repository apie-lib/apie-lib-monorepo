<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All language extlangs registered in the IANA Language Subtag Registry.
 *
 * @see https://www.iana.org/assignments/language-subtag-registry
 *
 * Any language extlang that is not active anymore can be used as well (for data integrity).
 */
final class LanguageExtlang implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-extlangs.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
