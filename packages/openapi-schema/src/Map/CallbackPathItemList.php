<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\Spec\Callback;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\TypeUtilInterface;

final class CallbackPathItemList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    private function __construct()
    {
    }

    protected static function getWantedType(string $key): TypeUtilInterface
    {
        if (!preg_match(Constants::VALID_KEY_REGEX, $key)) {
            throw new InvalidKeyException($key, new CallbackPathItemList());
        }
        return new AnotherValueObject($key, Callback::class);
    }
}
