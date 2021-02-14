<?php


namespace Apie\ValueObjectPlugin\SchemaProviders;

use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\SchemaGenerator\Context\SchemaContext;
use Apie\SchemaGenerator\Contracts\SchemaProvider;
use Apie\ValueObjects\StringCaseInsensitiveEnumTrait;
use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\StringTrait;
use ReflectionClass;

class StringEnumTraitProvider extends StringTraitProvider
{
    public function supports(ReflectionClass $class, ?SchemaContext $schemaContext = null): bool
    {
        return in_array(StringEnumTrait::class, class_uses($class->name)) ||
            in_array(StringCaseInsensitiveEnumTrait::class, class_uses($class->name));
    }

    public function toSchema(ReflectionClass $class, ?SchemaContext $schemaContext = null): SchemaContract
    {
        return parent::toSchema($class, $schemaContext)
            ->with('enums', array_values($class->getMethod('getValidValues')->invoke(null)));
    }
}
