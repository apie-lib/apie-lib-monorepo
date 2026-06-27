<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All language regions registered in the IANA Language Subtag Registry.
 *
 * @see BCP 47
 * @see RFC 5646
 * @see RFC 4647
 * @see https://www.iana.org/assignments/language-subtag-registry
 *
 * Any language region that is not active anymore can be used as well (for data integrity).
 */
final class LanguageRegion implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-regions.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
