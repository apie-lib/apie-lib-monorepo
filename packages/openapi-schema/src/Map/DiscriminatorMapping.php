<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\OpenapiSchema\Spec\Reference;
use Apie\TypeJuggling\StringLiteral;
use Apie\TypeJuggling\TypeUtilInterface;

/**
 * use regular expression used in Reference to only allow references
 * @see Reference
 */
class DiscriminatorMapping implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        return new StringLiteral($fieldName);
    }
}
