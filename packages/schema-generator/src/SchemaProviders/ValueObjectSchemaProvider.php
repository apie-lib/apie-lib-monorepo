<?php
namespace Apie\SchemaGenerator\SchemaProviders;

use Apie\Core\RegexUtils;
use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\SchemaGenerator\Builders\ComponentsBuilder;
use Apie\SchemaGenerator\Interfaces\SchemaProvider;
use cebe\openapi\spec\Components;
use ReflectionClass;

/**
 * Gets schema data from the toNative() return type hint.
 * @implements SchemaProvider<ValueObjectInterface>
 */
class ValueObjectSchemaProvider implements SchemaProvider
{
    public function supports(ReflectionClass $class): bool
    {
        return $class->implementsInterface(ValueObjectInterface::class) && !in_array(CompositeValueObject::class, $class->getTraitNames());
    }

    public function addDisplaySchemaFor(
        ComponentsBuilder $componentsBuilder,
        string $componentIdentifier,
        ReflectionClass $class,
        bool $nullable = false
    ): Components {
        return $this->getSchema($componentsBuilder, $componentIdentifier, $class, true, $nullable);
    }

    public function addCreationSchemaFor(
        ComponentsBuilder $componentsBuilder,
        string $componentIdentifier,
        ReflectionClass $class,
        bool $nullable = false
    ): Components {
        return $this->getSchema($componentsBuilder, $componentIdentifier, $class, false, $nullable);
    }

    /**
     * @param ReflectionClass<ValueObjectInterface> $class
     */
    private function getSchema(
        ComponentsBuilder $componentsBuilder,
        string $componentIdentifier,
        ReflectionClass $class,
        bool $display,
        bool $nullable,
    ): Components {
        $type = $class->getMethod('toNative')->getReturnType();
        $schema = $componentsBuilder->getSchemaForType($type, false, $display, $nullable);

        if ($class->implementsInterface(HasRegexValueObjectInterface::class)) {
            $className = $class->name;
            $schema->pattern = RegexUtils::removeDelimiters($className::getRegularExpression());
        }
        $componentsBuilder->setSchema($componentIdentifier, $schema);

        return $componentsBuilder->getComponents();
    }
}
