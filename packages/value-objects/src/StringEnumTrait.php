<?php


namespace Apie\ValueObjects;

use ReflectionClass;

trait StringEnumTrait
{
    use StringTrait;

    /**
     * @var string[]
     */
    private static $lookupTable;

    final protected function validValue(string $value): bool
    {
        $values = self::getLookupTable();
        return isset($values[$value]);
    }

    final protected function sanitizeValue(string $value): string
    {
        $values = self::getLookupTable();
        assert(isset($values[$value]));
        return $values[$value];
    }

    private static function getLookupTable(): array
    {
        if (!self::$lookupTable) {
            $values = self::getValidValues();
            self::$lookupTable = [];
            foreach ($values as $value) {
                self::$lookupTable[$value] = $value;
            }
            foreach ($values as $key => $value) {
                self::$lookupTable[$key] = $value;
            }
        }
        return self::$lookupTable;
    }

    final public static function getValidValues(): array
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }
}
