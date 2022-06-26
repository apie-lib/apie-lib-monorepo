<?php
namespace Apie\Fixtures\Incorrect\ValueObjects;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

/**
 * Example of incorrect value object where a property has no typehint.
 */
class CompositeValueObjectWithoutTypehint implements ValueObjectInterface
{
    private $noTypehint;

    use CompositeValueObject;
}