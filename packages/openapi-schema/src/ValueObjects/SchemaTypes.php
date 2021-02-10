<?php


namespace Apie\OpenapiSchema\ValueObjects;

use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * Enum for all allowed types in a schema.
 */
class SchemaTypes implements ValueObjectInterface
{
    use StringEnumTrait;

    const ARRAY = 'array';

    const INTEGER = 'integer';

    const FLOAT = 'float';

    const STRING = 'string';

    const OBJECT = 'object';

    const BOOLEAN = 'boolean';

    const NUMBER = 'number';

    public function getRequiredFields()
    {
        return [];
    }

    public function getConflictedFields()
    {
        $conflictedFields = [];
        if ($this->value !== self::OBJECT) {
            array_push(
                $conflictedFields,
                'properties',
                'additionalProperties',
                'required',
                'minProperties',
                'maxProperties'
            );
        }
        if ($this->value !== self::STRING) {
            array_push(
                $conflictedFields,
                'minLength',
                'maxLength',
                'pattern',
                'minProperties',
                'maxProperties'
            );
        }
        if ($this->value !== self::ARRAY) {
            array_push(
                $conflictedFields,
                'items',
                'minItems',
                'maxItems',
                'uniqueItems'
            );
        }
        if (!in_array($this->value, [self::NUMBER, self::INTEGER])) {
            array_push(
                $conflictedFields,
                'multipleOf',
                'minimum',
                'exclusiveMinimum',
                'maximum',
                'exclusiveMaximum'
            );
        }
        if (!in_array($this->value, [self::NUMBER, self::INTEGER, self::STRING])) {
            array_push(
                $conflictedFields,
                'format',
                'enum'
            );
        }
        return $conflictedFields;
    }
}
