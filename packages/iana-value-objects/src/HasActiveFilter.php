<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;

trait HasActiveFilter
{
    abstract protected static function getActiveData(): array;
    private static StringSet $activeOptions;

    protected static function requiresActive(): bool
    {
        return true;
    }

    public static function getOptions(): StringSet
    {
        if (!isset(static::$activeOptions)) {
            static::$activeOptions = new StringSet(array_keys(static::getActiveData()));
        }
        return static::$activeOptions;
    }
}