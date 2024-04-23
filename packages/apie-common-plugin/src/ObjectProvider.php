<?php

namespace Apie\ApieCommonPlugin;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use ReflectionClass;
use ReflectionException;

abstract class ObjectProvider
{
    private const DEFINED_GETTERS = [
        'Entities' => EntityInterface::class,
        'ValueObjects' => ValueObjectInterface::class,
        'Lists' => ItemList::class,
        'Hashmaps' => ItemHashmap::class,
        'Dtos' => DtoInterface::class,
    ];

    protected const DEFINED_CLASSES = [];

    /**
     * @var array<string, array<string, array<int, string>>>
     */
    private static array $mapping = [];

    /**
     * @var array<string, array<int, string>>
     */
    private static array $mappedServices = [];

    private static function getMapping(): array
    {
        $key = static::class;
        if (!isset(self::$mapping[$key])) {
            self::$mapping = [];
            foreach (self::DEFINED_GETTERS as $name => $interface) {
                self::$mapping[$key][$name] = [];
            }
            foreach (static::DEFINED_CLASSES as $apieObject) {
                try {
                    $refl = new ReflectionClass($apieObject);
                    $interfaceNames = [$refl->name, ...$refl->getInterfaceNames()];
                    while ($refl = $refl->getParentClass()) {
                        $interfaceNames[] = $refl->name;
                    }
                    foreach (self::DEFINED_GETTERS as $name => $interfaceName) {
                        if (in_array($interfaceName, $interfaceNames)) {
                            self::$mapping[$key][$name][] = $apieObject;
                        }
                    }
                } catch (ReflectionException) {
                }
            }
        }
        return self::$mapping[$key];
    }

    final public static function getAvailableServices(): array
    {
        $key = static::class;
        if (!isset(self::$mappedServices[$key])) {
            self::$mappedServices[$key] = [];
            foreach (static::DEFINED_CLASSES as $apieObject) {
                try {
                    $refl = new ReflectionClass($apieObject);
                    $interfaceNames = [$refl->name, ...$refl->getInterfaceNames()];
                    while ($refl = $refl->getParentClass()) {
                        $interfaceNames[] = $refl->name;
                    }
                    foreach (self::DEFINED_GETTERS as $interfaceName) {
                        if (in_array($interfaceName, $interfaceNames)) {
                            continue(2);
                        }
                    }
                    self::$mappedServices[$key][] = $apieObject;
                } catch (ReflectionException) {
                }
            }

        }
        return self::$mappedServices[$key];
    }


    final public static function getAvailableValueObjects(): array
    {
        return static::getMapping()['ValueObjects'] ?? [];
    }

    final public static function getAvailableLists(): array
    {
        return static::getMapping()['Lists'] ?? [];
    }

    final public static function getAvailableHashmaps(): array
    {
        return static::getMapping()['Hashmaps'] ?? [];
    }

    final public static function getAvailableDtos(): array
    {
        return static::getMapping()['Dtos'] ?? [];
    }
}
