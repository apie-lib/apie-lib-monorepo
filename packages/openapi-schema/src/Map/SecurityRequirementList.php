<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Spec\SecurityRequirement;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\TypeUtilInterface;

class SecurityRequirementList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    protected static function getWantedType(string $key): TypeUtilInterface
    {
        return new AnotherValueObject($key, SecurityRequirement::class);
    }
}
