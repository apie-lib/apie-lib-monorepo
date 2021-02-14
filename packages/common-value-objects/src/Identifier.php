<?php


namespace Apie\CommonValueObjects;

use Apie\Core\Interfaces\HasSchemaInformationContract;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class Identifier implements ValueObjectInterface, HasSchemaInformationContract
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $value) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }


    public static function toSchema(): SchemaContract
    {
        return SchemaFactory::createStringSchema('identifier')
            ->with('pattern', '^[a-zA-Z_][a-zA-Z0-9_]*$');
    }
}
