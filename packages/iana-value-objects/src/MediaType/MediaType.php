<?php
namespace Apie\IanaValueObjects\MediaType;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All media types (MIME types) registered in the IANA Media Types Registry.
 *
 * @see https://www.iana.org/assignments/media-types/media-types.xhtml
 *
 * Any media type that is not active anymore can be used as well (for data integrity).
 */
final class MediaType implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsMediaType;

    protected static function requiresActive(): bool
    {
        return false;
    }

    

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
    }
}
