<?php
namespace Apie\Core\ValueObjects;

use ReflectionClass;

class Utils
{
    private function __construct()
    {
    }

    public static function getDisplayNameForValueObject(ValueObjectInterface|ReflectionClass $class): string
    {
        if ($class instanceof ReflectionClass) {
            $className = $class->getShortName();
        } else {
            $className = (new ReflectionClass($class))->getShortName();
        }
        if (strcasecmp($className, 'Abstract') === 0 || strcasecmp($className, 'AbstractInterface') === 0) {
            return 'Abstract';
        }
        return preg_replace(
            '/Interface/i',
            '',
            preg_replace('/^abstract/i', '', $className)
        );
    }
}
