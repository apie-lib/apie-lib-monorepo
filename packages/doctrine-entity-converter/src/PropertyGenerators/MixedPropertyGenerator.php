<?php
namespace Apie\DoctrineEntityConverter\PropertyGenerators;

use Apie\DoctrineEntityConverter\Embeddable\MixedType;
use Apie\DoctrineEntityConverter\Interfaces\PropertyGeneratorInterface;
use Apie\DoctrineEntityConverter\Utils\Utils;
use ReflectionClass;
use ReflectionProperty;

class MixedPropertyGenerator implements PropertyGeneratorInterface
{
    public function isSupported(ReflectionClass $class, ReflectionProperty $property): bool
    {
        return true;
    }

    public function generateFromCode(ReflectionClass $class, ReflectionProperty $property): string
    {
        return sprintf(
            '%s::setProperty($instance, new ReflectionProperty(%s), %s::fromCode($input));',
            Utils::class,
            var_export($property->name, true),
            MixedType::class,
        );
    }
    public function generateInject(ReflectionClass $class, ReflectionProperty $property): string
    {
        // TODO
        return '';
    }
}