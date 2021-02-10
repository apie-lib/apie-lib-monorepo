<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\OpenapiSchema\Spec\Reference;
use Apie\OpenapiSchema\Spec\RequestBody;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\TypeUtilInterface;

class RequestBodyMap implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        if (!preg_match('/^[a-zA-Z0-9\.\-_]+$/', $fieldName)) {
            throw new InvalidKeyException($fieldName, null);
        }
        return new Compound(
            $fieldName,
            new AnotherValueObject($fieldName, RequestBody::class),
            new AnotherValueObject($fieldName, Reference::class)
        );
    }
}
