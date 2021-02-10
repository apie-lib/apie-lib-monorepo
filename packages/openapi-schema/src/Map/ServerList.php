<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Spec\Server;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\TypeUtilInterface;

class ServerList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    protected static function getWantedType(string $key): TypeUtilInterface
    {
        return new AnotherValueObject($key, Server::class);
    }
}
