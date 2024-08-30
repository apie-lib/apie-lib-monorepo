<?php
namespace Apie\Maker\BoundedContext\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\Maker\BoundedContext\Resources\ResourceDefinition;
use ReflectionClass;

final class ResourceDefinitionIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ResourceDefinition::class);
    }
}
