<?php
namespace Apie\Core\ValueObjects;

class Utils {
    private function __construct()
    {
    }

    public static function getDisplayNameForValueObject(ValueObjectInterface|ReflectionClass $class): string
    {
        $className = (new ReflectionClass($class))->getShortName();
        if (strcasecmp($className, 'Abstract') === 0) {
            return 'Abstract';
        }
        return preg_replace(
            '/Interface/i',
            '',
            preg_replace('/^abstract/i', '', $className)
        );
    }
}