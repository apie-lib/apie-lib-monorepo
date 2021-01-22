<?php


namespace Apie\ValueObjects;

use Apie\OpenapiSchema\Contract\SchemaContract;
use ReflectionClass;

trait StringEnumTrait
{
    use StringTrait;

    final protected function validValue(string $value): bool
    {
        $values = self::getValidValues();
        return isset($values[$value]) || false !== array_search($value, $values, true);
    }

    final protected function sanitizeValue(string $value): string
    {
        $values = self::getValidValues();
        if (isset($values[$value])) {
            return $values[$value];
        }
        return $value;
    }


    final public static function getValidValues()
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }
}