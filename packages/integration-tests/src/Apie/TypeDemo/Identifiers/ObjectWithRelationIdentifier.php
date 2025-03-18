<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\ObjectWithRelation;
use ReflectionClass;

/**
 * @implements IdentifierInterface<ObjectWithRelation>
 */
final class ObjectWithRelationIdentifier extends Ulid implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ObjectWithRelation::class);
    }
}
