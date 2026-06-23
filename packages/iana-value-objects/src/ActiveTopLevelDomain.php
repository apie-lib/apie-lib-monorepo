<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All Top-Level Domains (TLDs) registered in the IANA TLD Database.
 *
 * @see https://data.iana.org/TLD/tlds-alpha-by-domain.txt
 *
 * Only active TLDs can be used.
 */
final class ActiveTopLevelDomain implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject, IsSanitizingInput {
        IsSanitizingInput::convert insteadof StaticDataValueObject;
    }

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/tlds.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
