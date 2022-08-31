<?php
namespace Apie\Core\Actions;

use Apie\Core\Lists\StringList;
use ReflectionClass;
use ReflectionMethod;

interface MethodActionInterface extends ActionInterface
{
    public static function getRouteAttributes(ReflectionClass $class, ?ReflectionMethod $method = null): array;
    public static function getDescription(ReflectionClass $class, ?ReflectionMethod $method = null): string;
    public static function getInputType(ReflectionClass $class, ?ReflectionMethod $method = null): ReflectionMethod;
    public static function getOutputType(ReflectionClass $class, ?ReflectionMethod $method = null): ReflectionMethod|ReflectionClass;
    public static function getPossibleActionResponseStatuses(?ReflectionMethod $method = null): ActionResponseStatusList;
    public static function getTags(ReflectionClass $class, ?ReflectionMethod $method = null): StringList;
}