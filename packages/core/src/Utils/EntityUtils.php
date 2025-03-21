<?php
namespace Apie\Core\Utils;

use Apie\Core\Attributes\Context;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Exceptions\IndexNotFoundException;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Other\DiscriminatorMapping;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;

final class EntityUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param string|ReflectionClass<object>|ReflectionProperty|ReflectionType|ReflectionMethod $input
     */
    public static function isEntity(string|ReflectionClass|ReflectionProperty|ReflectionType|ReflectionMethod $input): bool
    {
        try {
            $class = ConverterUtils::toReflectionClass($input);
        } catch (ReflectionException) {
            return false;
        }
        return $class !== null
            && $class->implementsInterface(EntityInterface::class)
            && !$class->isInterface();
    }

    /**
     * @param string|ReflectionClass<object>|ReflectionProperty|ReflectionType|ReflectionMethod $input
     */
    public static function isNonPolymorphicEntity(string|ReflectionClass|ReflectionProperty|ReflectionType|ReflectionMethod $input): bool
    {
        try {
            $class = ConverterUtils::toReflectionClass($input);
        } catch (ReflectionException) {
            return false;
        }
        return $class !== null
            && !$class->implementsInterface(PolymorphicEntityInterface::class)
            && $class->implementsInterface(EntityInterface::class)
            && !$class->isInterface();
    }

    /**
     * @param string|ReflectionClass<object>|ReflectionProperty|ReflectionType|ReflectionMethod $input
     */
    public static function isPolymorphicEntity(string|ReflectionClass|ReflectionProperty|ReflectionType|ReflectionMethod $input): bool
    {
        try {
            $class = ConverterUtils::toReflectionClass($input);
        } catch (ReflectionException) {
            return false;
        }
        return $class !== null
            && $class->implementsInterface(PolymorphicEntityInterface::class)
            && !$class->isInterface();
    }

    /**
     * @param ReflectionClass<PolymorphicEntityInterface>|null $base
     * @return array<string, string>
     */
    public static function getDiscriminatorValues(PolymorphicEntityInterface $entity, ?ReflectionClass $base = null): array
    {
        if (!$base) {
            $refl = new ReflectionClass($entity);
            while ($refl) {
                if ($refl->getMethod('getDiscriminatorMapping')->getDeclaringClass()->name === $refl->name) {
                    $base = $refl;
                }
                $refl = $refl->getParentClass();
            }
        }
        assert($base !== null);
        $entityClass = get_class($entity);
        $result = [];
        $current = $base;
        $last = null;
        while ($current->getMethod('getDiscriminatorMapping')->getDeclaringClass()->name !== $last && $current->name !== $entityClass) {
            /** @var DiscriminatorMapping $mapping */
            $mapping = $current->getMethod('getDiscriminatorMapping')->invoke(null);
            $config = $mapping->getConfigForClass($entity);
            $result[$mapping->getPropertyName()] = $config->getDiscriminator();
            $last = $current->getMethod('getDiscriminatorMapping')->getDeclaringClass()->name;
            $current = new ReflectionClass($config->getClassName());
        }
        return $result;
    }

    /**
     * @param ReflectionClass<PolymorphicEntityInterface> $base
     */
    public static function getDiscriminatorClasses(ReflectionClass $base): ReflectionClassList
    {
        $list = [];
        /** @var DiscriminatorMapping $mapping */
        $mapping = $base->getMethod('getDiscriminatorMapping')->invoke(null);
        foreach ($mapping->getConfigs() as $config) {
            $refl = new ReflectionClass($config->getClassName());
            if ($refl->isInstantiable()) {
                $list[] = $refl;
            } else {
                $list = [...$list, ...self::getDiscriminatorClasses($refl)];
            }
        }
        return new ReflectionClassList($list);
    }

    /**
     * Returns context related parameters of a method. This depends on the method type.
     * A context related parameter gets his value not from user input, but from a
     * Apie context variable.
     *
     * @return ReflectionParameter[]
     */
    public static function getContextParameters(ReflectionMethod $method): array
    {
        // getters: all arguments are context related
        if (preg_match('/^(get|is|has).+/i', $method->name)) {
            return $method->getParameters();
        }
        // setters: all arguments, except the last one
        if (preg_match('/^set.+/i', $method->name)) {
            $parameters = $method->getParameters();
            array_pop($parameters);
            return $parameters;
        }
        // other methods: constructor, actions. Only with #[Context] attributes.
        $parameters = [];
        foreach ($method->getParameters() as $parameter) {
            $attributes = $parameter->getAttributes(Context::class);
            if (!empty($attributes)) {
                $parameters[] = $parameter;
            }
        }
        return $parameters;
    }

    /**
     * Converts discriminator mappings from an array into the linked class.
     *
     * @template T of PolymorphicEntityInterface
     * @param array<string, string> $discriminators
     * @param ReflectionClass<T> $base
     * @return ReflectionClass<T>
     */
    public static function findClass(array $discriminators, ReflectionClass $base): ReflectionClass
    {
        /** @var DiscriminatorMapping $mapping */
        $mapping = $base->getMethod('getDiscriminatorMapping')->invoke(null);
        $value = $discriminators[$mapping->getPropertyName()] ?? null;
        if (!isset($value)) {
            throw new IndexNotFoundException($mapping->getPropertyName());
        }
        $current = new ReflectionClass($mapping->getClassNameFromDiscriminator($value));
        $last = $base->name;
        while ($current->getMethod('getDiscriminatorMapping')->getDeclaringClass()->name !== $last) {
            $mapping = $current->getMethod('getDiscriminatorMapping')->invoke(null);
            $value = $discriminators[$mapping->getPropertyName()] ?? null;
            if (!isset($value)) {
                throw new IndexNotFoundException($mapping->getPropertyName());
            }
            $last = $current->getMethod('getDiscriminatorMapping')->getDeclaringClass()->name;
            $current = new ReflectionClass($mapping->getClassNameFromDiscriminator($value));
        }

        return $current;
    }
}
