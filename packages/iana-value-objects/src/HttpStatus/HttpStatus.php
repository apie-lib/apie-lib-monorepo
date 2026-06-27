<?php
namespace Apie\IanaValueObjects\HttpStatus;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\StaticDataValueObject;

/**
 * All HTTP status codes registered in the IANA HTTP Status Codes Registry.
 *
 * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
 *
 * Any HTTP status code that is not active/valid anymore (e.g. obsolete) can be used as well (for data integrity).
 */
final class HttpStatus implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsHttpStatus;
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return false;
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
