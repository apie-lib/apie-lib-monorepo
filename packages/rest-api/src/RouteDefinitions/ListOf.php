<?php
namespace Apie\RestApi\RouteDefinitions;

use ReflectionClass;
use ReflectionMethod;
use ReflectionType;

final class ListOf
{
    /**
     * @param ReflectionClass<object>|ReflectionMethod|ReflectionType $type
     */
    public function __construct(
        public readonly ReflectionClass|ReflectionMethod|ReflectionType $type
    ) {
    }
}
