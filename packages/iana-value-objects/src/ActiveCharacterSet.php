<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All character sets registered in the IANA Character Sets Registry.
 *
 * @see https://www.iana.org/assignments/character-sets/character-sets.xhtml
 *
 * Only active character sets can be used.
 */
final class ActiveCharacterSet implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/character-sets.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
