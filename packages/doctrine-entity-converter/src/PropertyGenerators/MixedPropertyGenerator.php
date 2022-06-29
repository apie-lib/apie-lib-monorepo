<?php
namespace Apie\DoctrineEntityConverter\PropertyGenerators;

use Apie\DoctrineEntityConverter\Embeddables\MixedType;
use Apie\DoctrineEntityConverter\Interfaces\PropertyGeneratorInterface;
use Apie\DoctrineEntityConverter\Mediators\GeneratedCode;
use Doctrine\ORM\Mapping\Embedded;
use ReflectionClass;
use ReflectionProperty;

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
        $code->addProperty(MixedType::class, $property->name)
            ->addAttribute(Embedded::class, ['class' => MixedType::class]);
    }

    protected function generateFromCode(ReflectionClass $class, ReflectionProperty $property): string
    {
        return sprintf(
            'Utils::setProperty(
    $instance,
    new \ReflectionProperty(%s, %s),
    MixedType::fromCode($input)
);',
            var_export($property->getDeclaringClass()->name, true),
            var_export($property->name, true),
            MixedType::class,
        );
    }
    public function generateInject(ReflectionClass $class, ReflectionProperty $property): string
    {
        return sprintf(
            '$this->%s->inject($instance, new \ReflectionProperty(%s, %s));',
            $property->name,
            var_export($property->getDeclaringClass()->name, true),
            var_export($property->name, true)
        );
    }
}
