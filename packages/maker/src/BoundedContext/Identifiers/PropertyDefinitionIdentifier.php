<?php
namespace Apie\Maker\BoundedContext\Identifiers;

use Apie\Core\Identifiers\CamelCaseSlug;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Maker\BoundedContext\Entities\PropertyDefinition;
use ReflectionClass;

final class PropertyDefinitionIdentifier extends CamelCaseSlug implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(PropertyDefinition::class);
    }
}
