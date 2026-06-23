<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All port numbers registered in the IANA Port Numbers and Service Names Registry.
 *
 * @see https://www.iana.org/assignments/service-names-port-numbers/service-names-port-numbers.xhtml
 *
 * Only active port numbers can be used (not reserved or unassigned).
 */
final class ActivePortNumber implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/port-numbers.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }
}
