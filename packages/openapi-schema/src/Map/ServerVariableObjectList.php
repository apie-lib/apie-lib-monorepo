<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Factory\ReflectionTypeFactory;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\Spec\ServerVariable;
use ReflectionType;

final class ServerVariableObjectList implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(): ReflectionType
    {
        return ReflectionTypeFactory::createForClass(ServerVariable::class);
    }

    protected function isValidKey(string $key): bool
    {
        return preg_match(Constants::VALID_KEY_REGEX, $key) ? true : false;
    }
}