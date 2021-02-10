<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\Spec\ServerVariable;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\TypeUtilInterface;

final class ServerVariableObjectList implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        if (!preg_match(Constants::VALID_KEY_REGEX, $fieldName)) {
            throw new InvalidKeyException($fieldName, null);
        }
        return new AnotherValueObject($fieldName, ServerVariable::class);
    }
}
