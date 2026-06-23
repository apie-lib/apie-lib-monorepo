<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All media types (MIME types) registered in the IANA Media Types Registry.
 *
 * @see https://www.iana.org/assignments/media-types/media-types.xhtml
 *
 * Only active media types can be used.
 */
final class ActiveMediaType implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/media-types.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
