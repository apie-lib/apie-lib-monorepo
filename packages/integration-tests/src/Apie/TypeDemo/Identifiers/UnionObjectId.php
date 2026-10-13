<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\UnionObject;
use ReflectionClass;

/**
 * @implements IdentifierInterface<UnionObject>
 */
class UnionObjectId extends Ulid implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UnionObject::class);
    }
}
