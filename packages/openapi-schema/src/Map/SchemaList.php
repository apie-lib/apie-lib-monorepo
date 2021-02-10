<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Spec\Reference;
use Apie\OpenapiSchema\Spec\Schema;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\TypeUtilInterface;

class SchemaList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        return new Compound(
            $fieldName,
            new AnotherValueObject($fieldName, SchemaContract::class),
            new AnotherValueObject($fieldName, Schema::class),
            new AnotherValueObject($fieldName, Reference::class)
        );
    }
}
