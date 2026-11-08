<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\IanaObject;
use ReflectionClass;

/**
 * @implements IdentifierInterface<IanaObject>
 */
class IanaObjectIdentifier extends Ulid implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(IanaObject::class);
    }
}
