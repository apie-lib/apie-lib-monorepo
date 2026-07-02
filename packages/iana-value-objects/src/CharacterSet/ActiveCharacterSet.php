<?php
namespace Apie\IanaValueObjects\CharacterSet;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;
use Apie\IanaValueObjects\StaticDataValueObject;

/**
 * All character sets registered in the IANA Character Sets Registry.
 *
 * @see https://www.iana.org/assignments/character-sets/character-sets.xhtml
 *
 * Only active character sets can be used.
 */
final class ActiveCharacterSet implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsCharacterSet;
    use StaticDataValueObject;
    use HasActiveFilter;
}
