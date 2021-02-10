<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\OpenapiSchema\Spec\MediaType;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\StringLiteral;
use Apie\TypeJuggling\TypeUtilInterface;

class MediaTypeMap implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        if (!preg_match('#^([a-z-]+|\*)/([a-z-]+|\*)$#', $fieldName)) {
            throw new InvalidKeyException($fieldName);
        }
        return new Compound(
            $fieldName,
            new AnotherValueObject($fieldName, MediaType::class),
            new StringLiteral($fieldName)
        );
    }
}
