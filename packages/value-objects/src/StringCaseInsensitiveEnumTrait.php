<?php


namespace Apie\ValueObjects;

use ReflectionClass;

trait StringCaseInsensitiveEnumTrait
{
    use StringTrait;

    /**
     * @var string[]
     */
    private static $lookupTable;

    final protected function validValue(string $value): bool
    {
        $value = strtoupper($value);
        $values = self::getLookupTable();
        return isset($values[$value]);
    }

    final protected function sanitizeValue(string $value): string
    {
        $value = strtoupper($value);
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
                self::$lookupTable[strtoupper($value)] = $value;
            }
            foreach ($values as $key => $value) {
                self::$lookupTable[strtoupper($key)] = $value;
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
