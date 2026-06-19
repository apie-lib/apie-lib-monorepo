<?php
namespace Apie\IanaValueObjects;

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
final class LanguageRegion implements StringValueObjectInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/language-regions.php';
    }
}