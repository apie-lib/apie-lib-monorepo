<?php


namespace Apie\ValueObjectPlugin\SchemaProviders;

use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\SchemaGenerator\Context\SchemaContext;
use Apie\SchemaGenerator\Contracts\SchemaProvider;
use Apie\ValueObjects\StringTrait;
use ReflectionClass;

class StringTraitProvider implements SchemaProvider
{
    public function supports(ReflectionClass $class, ?SchemaContext $schemaContext = null): bool
    {
        return in_array(StringTrait::class, class_uses($class->name));
    }

    public function toSchema(ReflectionClass $class, ?SchemaContext $schemaContext = null): SchemaContract
    {
        return SchemaFactory::createStringSchema(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class->getShortName())));
    }
}