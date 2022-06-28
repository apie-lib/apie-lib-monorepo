<?php
namespace Apie\DoctrineEntityConverter\Embeddables;

use Doctrine\ORM\Mapping\Column;
use ReflectionProperty;

/**
 * Maps any type to a doctrine column. To allow any type, we basically store 
 */
class MixedType {
    #[Column(['type' => 'text'])]
    private ?string $serializedString = null;

    private function __construct()
    {
    }

    public static function createFrom(mixed $input): self
    {
        $instance = new self();
        $instance->serializedString = serialize($input);
        return $instance;
    }

    public function inject(object $instance, ReflectionProperty $property): void
    {
        $property->setAccessible(true);
        $property->setValue($instance, unserialize($this->serializedString));
    }
}