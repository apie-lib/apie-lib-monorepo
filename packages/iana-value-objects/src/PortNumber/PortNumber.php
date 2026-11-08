<?php
namespace Apie\IanaValueObjects\PortNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All port numbers registered in the IANA Port Numbers and Service Names Registry.
 *
 * @see https://www.iana.org/assignments/service-names-port-numbers/service-names-port-numbers.xhtml
 *
 * Any port number that is not active/valid anymore can be used as well (for data integrity).
 */
#[FakeMethod('createRandom')]
final class PortNumber implements StringValueObjectInterface
{
    use IsPortNumber;
}
