<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;

/**
 * All language scripts registered in the IANA Language Subtag Registry.
 *
 * @see https://www.iana.org/assignments/language-subtag-registry
 *
 * Only active language scripts can be used.
 */
final class ActiveLanguageScript implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsLanguageSubtag;
    use HasActiveFilter;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-scripts.php';
    }
}
