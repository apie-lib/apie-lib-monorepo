<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All HTTP status codes registered in the IANA HTTP Status Codes Registry.
 *
 * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
 *
 * Any HTTP status code that is not active/valid anymore (e.g. obsolete) can be used as well (for data integrity).
 */
final class HttpStatus implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/http-status-codes.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
