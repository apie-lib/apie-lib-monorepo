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
 * Only active language extlangs can be used.
 */
final class ActiveLanguageExtlang implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-extlangs.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
