<?php
namespace Apie\Fixtures\Incorrect\ValueObjects;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

/**
 * Example of incorrect value object where a property has a intersection typehint, which is not supported.
 */
class CompositeValueObjectWithIntersectionTypehint implements ValueObjectInterface
{
    private ValueObjectInterface&EntityInterface $intersection;

    use CompositeValueObject;
}
