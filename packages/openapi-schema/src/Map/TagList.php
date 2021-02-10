<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Spec\Tag;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\TypeUtilInterface;

class TagList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    protected static function getWantedType(string $key): TypeUtilInterface
    {
        return new AnotherValueObject($key, Tag::class);
    }
}
