<?php
namespace Apie\IanaValueObjects\HttpHeader;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\StaticDataValueObject;

/**
 * All message headers (HTTP fields) registered in the IANA HTTP Field Names Registry.
 *
 * @see https://www.iana.org/assignments/http-fields/http-fields.xhtml
 *
 * Any header that is not active/valid anymore (e.g. deprecated/obsoleted) can be used as well (for data integrity).
 */
final class HttpHeader implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsHttpHeader, StaticDataValueObject {
        IsHttpHeader::convert insteadof StaticDataValueObject;
    }

    protected static function requiresActive(): bool
    {
        return false;
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
