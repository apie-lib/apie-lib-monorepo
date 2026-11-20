<?php
namespace Apie\IanaValueObjects\CharacterSet;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\StaticDataValueObject;

/**
 * All character sets registered in the IANA Character Sets Registry.
 *
 * @see https://www.iana.org/assignments/character-sets/character-sets.xhtml
 *
 * Any character set that is not active anymore can be used as well (for data integrity).
 */
final class CharacterSet implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsCharacterSet;
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
