<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;

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
    use HasActiveFilter;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/language-extlangs.php';
    }
}
