<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\OrderLine;
use ReflectionClass;

/**
 * @implements IdentifierInterface<OrderLine>
 */
class OrderLineIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(OrderLine::class);
    }
}
