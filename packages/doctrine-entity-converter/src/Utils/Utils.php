<?php
namespace Apie\DoctrineEntityConverter\Utils;

use ReflectionProperty;

class Utils {
    private function __construct() {
    }

    public static function setProperty(mixed $instance, ReflectionProperty $property, mixed $value)
    {
        $property->setAccessible(true);
        $property->setValue($instance, $value);
    }
}