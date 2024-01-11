<?php
namespace Apie\Fixtures\Incorrect\ValueObjects;

use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

/**
 * Example of incorrect value object where a property has no typehint.
 */
class CompositeValueObjectWithoutTypehint implements ValueObjectInterface
{
    private $noTypehint;

    use CompositeValueObject;
}
