<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All URI schemes registered in the IANA URI Schemes Registry.
 *
 * @see https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
 *
 * Any URI scheme that is not active/valid anymore (e.g. historical) can be used as well (for data integrity).
 */
final class UriScheme implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/uri-schemes.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
