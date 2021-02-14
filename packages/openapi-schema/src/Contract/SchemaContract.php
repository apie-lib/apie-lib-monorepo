<?php


namespace Apie\OpenapiSchema\Contract;

use Apie\ValueObjects\ValueObjectInterface;

interface SchemaContract extends ValueObjectInterface
{
    public function with(string $key, $value): SchemaContract;
}
