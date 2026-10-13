<?php
namespace Apie\IanaValueObjects\PortNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All port numbers registered in the IANA Port Numbers and Service Names Registry.
 *
 * @see https://www.iana.org/assignments/service-names-port-numbers/service-names-port-numbers.xhtml
 *
 * Only active port numbers can be used (not reserved or unassigned).
 */
#[FakeMethod('createRandom')]
final class ActivePortNumber implements StringValueObjectInterface
{
    use IsPortNumber;
}
