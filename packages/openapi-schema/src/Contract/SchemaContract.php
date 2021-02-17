<?php


namespace Apie\OpenapiSchema\Contract;

use Apie\ValueObjects\ValueObjectInterface;

interface SchemaContract extends ValueObjectInterface
{
    /**
     * Creates new Schema with modified key.
     *
     * @param string $key
     * @param mixed $value
     * @return SchemaContract
     */
    public function with(string $key, $value): SchemaContract;

    /**
     * Creates new Schema with an added property added to 'properties'
     *
     * @param string $key
     * @param $propertySchema
     * @return SchemaContract
     */
    public function withProperty(string $key, $propertySchema): SchemaContract;
}
