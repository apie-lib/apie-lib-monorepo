<?php


namespace Apie\CommonValueObjects;

use Apie\Core\Interfaces\HasSchemaInformationContract;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class KebabCaseString implements ValueObjectInterface, HasSchemaInformationContract
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return preg_match('/^[a-zA-Z]+(-[a-zA-Z]+)*$/', $value) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }

    public static function toSchema(): SchemaContract
    {
        return SchemaFactory::createStringSchema()
            ->with('example', 'test-test-Test')
            ->with('pattern', '^[a-zA-Z]+(-[a-zA-Z]+)*$');
    }
}
