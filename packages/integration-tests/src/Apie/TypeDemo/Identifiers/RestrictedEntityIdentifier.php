<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\RestrictedEntity;
use ReflectionClass;

/**
 * @implements IdentifierInterface<RestrictedEntity>
 */
final class RestrictedEntityIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(RestrictedEntity::class);
    }
}
