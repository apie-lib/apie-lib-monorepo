<?php
namespace Apie\IanaValueObjects;

use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All language regions registered in the IANA Language Subtag Registry.
 * 
 * Only active language regions can be used.
 */
final class ActiveLanguageRegion implements StringValueObjectInterface
{
    use IsLanguageSubtag;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/language-regions.php';
    }
}