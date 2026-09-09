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
 * Only active/assigned HTTP status codes can be used.
 */
final class ActiveHttpStatus implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsHttpStatus;
    use StaticDataValueObject;

    private static StringSet $activeOptions;

    public static function getOptions(): StringSet
    {
        if (!isset(static::$activeOptions)) {
            static::$activeOptions = new StringSet(array_map('strval', array_keys(static::getActiveData())));
        }
        return static::$activeOptions;
    }

    protected static function requiresActive(): bool
    {
        return true;
    }
}
