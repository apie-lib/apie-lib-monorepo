<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\AutoIncrementInteger;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Order;
use ReflectionClass;

/**
 * @implements IdentifierInterface<Order>
 */
class OrderIdentifier extends AutoIncrementInteger implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(Order::class);
    }
}
