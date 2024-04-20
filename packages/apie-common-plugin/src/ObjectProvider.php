<?php

namespace Apie\ApieCommonPlugin;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use ReflectionClass;
use ReflectionException;

abstract class ObjectProvider
{
    private const DEFINED_GETTERS = [
        'ValueObjects' => ValueObjectInterface::class,
        'Lists' => ItemList::class,
        'Hashmaps' => ItemHashmap::class,
        'Dtos' => DtoInterface::class,
    ];

    protected const DEFINED_CLASSES = [];

    private static array $mapping;

    private static function getMapping(): array
    {
        if (!isset(self::$mapping)) {
            self::$mapping = [];
            foreach (self::DEFINED_GETTERS as $name => $interface) {
                self::$mapping[$name] = [];
            }
            foreach (self::DEFINED_CLASSES as $apieObject) {
                try {
                    $refl = new ReflectionClass($apieObject);
                    $interfaceNames = [$refl->getInterfaceNames()];
                    while ($refl = $refl->getParentClass()) {
                        $interfaceNames[] = $refl->name;
                    }
                    foreach (self::DEFINED_GETTERS as $name => $interface) {
                        if (in_array($interface, $interfaceNames)) {
                            self::$mapping[$name][] = $apieObject;
                        }
                    }
                } catch (ReflectionException) {
                }
            }
        }
        return self::$mapping;
    }


    final public static function getAvailableValueObjects(): array
    {
        return self::getMapping()['ValueObjects'] ?? [];
    }

    final public static function getAvailableLists(): array
    {
        return self::getMapping()['Lists'] ?? [];
    }

    final public static function getAvailableHashmaps(): array
    {
        return self::getMapping()['Hashmaps'] ?? [];
    }

    final public static function getAvailableDtos(): array
    {
        return self::getMapping()['Dtos'] ?? [];
    }
}
