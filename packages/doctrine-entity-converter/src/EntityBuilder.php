<?php
namespace Apie\DoctrineEntityConverter;

use Apie\DoctrineEntityConverter\Interfaces\PropertyGeneratorInterface;
use Apie\DoctrineEntityConverter\Mediators\GeneratedCode;
use ReflectionClass;

class EntityBuilder {
    private array $propertyGenerators;
    public function __construct(private string $namespace, PropertyGeneratorInterface... $propertyGenerators) {
        $this->propertyGenerators = $propertyGenerators;
    }

    public function createCodeFor(ReflectionClass $className): string
    {
        $generatedCode = new GeneratedCode($this->namespace . $className->getShortName());
        foreach ($className->getProperties() as $property) {
            foreach ($this->propertyGenerators as $propertyGenerator) {
                if ($propertyGenerator->isSupported($className, $property)) {
                    $fromCode = $propertyGenerator->generateFromCode($className, $property);
                    $generatedCode->addCreateFromCode($fromCode);
                    $inject = $propertyGenerator->generateInject($className, $property);
                    $generatedCode->addInjectCode($inject);
                    break;
                }
            }
        }
        return $generatedCode->toCode();
    }
}