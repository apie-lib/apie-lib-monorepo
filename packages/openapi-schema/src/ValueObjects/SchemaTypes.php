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

    const INT = 'int';
}