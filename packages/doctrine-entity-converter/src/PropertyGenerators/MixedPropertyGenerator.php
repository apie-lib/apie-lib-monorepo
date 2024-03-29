<?php
namespace Apie\DoctrineEntityConverter\PropertyGenerators;

use Apie\DoctrineEntityConverter\Embeddables\MixedType;
use Apie\DoctrineEntityConverter\Interfaces\PropertyGeneratorInterface;
use Apie\DoctrineEntityConverter\Mediators\GeneratedCode;
use Apie\DoctrineEntityConverter\Utils\Utils;
use Doctrine\ORM\Mapping\Embedded;
use ReflectionClass;
use ReflectionProperty;

/**
 * @template T of object
 * @implements PropertyGeneratorInterface<T>
 */
class MixedPropertyGenerator implements PropertyGeneratorInterface
{
    public function isSupported(ReflectionClass $class, ReflectionProperty $property): bool
    {
        return true;
    }

    public function apply(GeneratedCode $code, ReflectionClass $class, ReflectionProperty $property): void
    {
        $code->addUse(MixedType::class);
        $fromCode = $this->generateFromCode($class, $property);
        $code->addCreateFromCode($fromCode);
        $inject = $this->generateInject($class, $property);
        $code->addInjectCode($inject);
        $prop = $code->addProperty(MixedType::class, $property->name);
        $prop->addAttribute(Embedded::class, ['class' => MixedType::class]);
        $type = $property->getType();

        Utils::addIdAttributeIfApplicable($class->name, $type, $prop);
    }

    /**
     * @param ReflectionClass<T> $class
     */
    protected function generateFromCode(ReflectionClass $class, ReflectionProperty $property): string
    {
        $declaringClass = 'OriginalDomainObject';
        if ($property->getDeclaringClass()->name !== $class->name) {
            $declaringClass = '\\' . $property->getDeclaringClass()->name;
        }
        return sprintf(
            '$instance->%s = MixedType::createFrom(Utils::getProperty($input, new \ReflectionProperty(%s::class, %s)));',
            $property->name,
            $declaringClass,
            var_export($property->name, true)
        );
    }

    /**
     * @param ReflectionClass<T> $class
     */
    protected function generateInject(ReflectionClass $class, ReflectionProperty $property): string
    {
        $declaringClass = 'OriginalDomainObject';
        if ($property->getDeclaringClass()->name !== $class->name) {
            $declaringClass = '\\' . $property->getDeclaringClass()->name;
        }
        return sprintf(
            'Utils::setProperty($instance, new \ReflectionProperty(%s::class, %s), $this->%s->toDomainObject());',
            $declaringClass,
            var_export($property->name, true),
            $property->name
        );
    }
}
