<?php

namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\TypeJuggling\StringLiteral;
use Apie\TypeJuggling\TypeUtilInterface;

class ScopesMap implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        if (!preg_match('/^[a-zA-Z][a-zA-Z:0-9]*$/', $fieldName)) {
            throw new InvalidKeyException($fieldName);
        }
        return new StringLiteral($fieldName);
    }
}
