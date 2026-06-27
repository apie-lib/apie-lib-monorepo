<?php
namespace Apie\IanaValueObjects\TopLevelDomain;

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
    use IsTopLevelDomain;

    protected static function requiresActive(): bool
    {
        return true;
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
