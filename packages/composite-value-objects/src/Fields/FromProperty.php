<?php
namespace Apie\CompositeValueObjects\Fields;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\ValueObjects\Utils;
use Apie\Core\ValueObjects\ValueObjectInterface;
use ReflectionIntersectionType;
use ReflectionProperty;
use UnitEnum;

final class FromProperty implements FieldInterface
{
    private ReflectionProperty $property;

    public function __construct(ReflectionProperty $property)
    {
        $this->property = $property;
        $property->setAccessible(true);
    }

    public function fromNative(ValueObjectInterface $instance, mixed $value)
    {
        $type = $this->property->getType();
        if (null === $type || $type instanceof ReflectionIntersectionType) {
            throw new InvalidTypeException($type, 'ReflectionUnionType|ReflectionNamedType');
        }
        self::fillField($instance, Utils::toTypehint($type, $value));
    }

    public function fillField(ValueObjectInterface $instance, mixed $value)
    {
        $this->property->setValue($instance, $value);
    }

    public function fillMissingField(ValueObjectInterface $instance)
    {
        if (!$this->property->getType()->allowsNull()) {
            throw new InvalidTypeException('(missing value)', $this->property->getType()->getName());
        }
        $this->property->setValue($instance, null);
    }

    public function getValue(ValueObjectInterface $instance): mixed
    {
        return $this->property->getValue($instance);
    }

    public function toNative(ValueObjectInterface $instance): array|string|int|float|bool|UnitEnum
    {
        $value = $this->getValue($instance);
        return Utils::toNative($value);
    }
}