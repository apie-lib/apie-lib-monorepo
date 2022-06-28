<?php
namespace Apie\DoctrineEntityConverter\Interfaces;

use ReflectionClass;
use ReflectionProperty;

interface PropertyGeneratorInterface {
    public function isSupported(ReflectionClass $class, ReflectionProperty $property): bool;
    public function generateFromCode(ReflectionClass $class, ReflectionProperty $property): string;
    public function generateInject(ReflectionClass $class, ReflectionProperty $property): string;
}