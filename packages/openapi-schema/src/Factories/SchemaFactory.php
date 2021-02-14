<?php


namespace Apie\OpenapiSchema\Factories;

use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Spec\Schema;
use ReflectionClass;

class SchemaFactory
{
    public static function createObjectSchemaWithoutProperties(
        ReflectionClass $class,
        string $operation = 'get',
        array $groups = []
    ): SchemaContract {
        $description = $class->getShortName() . ' ' . $operation;
        if ($groups) {
            $description .= ' for groups ' . implode(', ', $groups);
        }
        return Schema::fromNative([
            'type' => 'object',
            'properties' => [],
            'title' => $class->getShortName(),
            'description' => $description,
        ]);
    }

    public static function createAnyTypeSchema(): Schema
    {
        return Schema::fromNative([]);
    }

    public static function createStringSchema(?string $format = null, ?string $defaultValue = null, bool $nullable = false): Schema
    {
        $data = ['type' => 'string'];
        if ($format !== null) {
            $data['format'] = $format;
        }
        if ($nullable) {
            $data['nullable'] = $nullable;
        }
        if ($defaultValue !== null) {
            $data['example'] = $defaultValue;
            $data['default'] = $defaultValue;
        }
        return Schema::fromNative($data);
    }

    public static function createBooleanSchema(): Schema
    {
        return Schema::fromNative(['type' => 'boolean']);
    }

    public static function createNumberSchema(?string $format = null): Schema
    {
        return Schema::fromNative(['type' => 'number', 'format' => $format]);
    }

    public static function createFloatSchema(): Schema
    {
        return self::createNumberSchema('double');
    }
}
