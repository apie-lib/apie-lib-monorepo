<?php
namespace Apie\Fixtures\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\Fixtures\Entities\Order;
use ReflectionClass;

class OrderIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(Order::class);
    }
}
