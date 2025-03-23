<?php
namespace Apie\Core\ValueObjects;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\TypeUtils;
use Apie\Core\Utils\ConverterUtils;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\TypeConverter\ReflectionTypeFactory;
use Apie\TypeConverter\Utils\ReflectionTypeUtil;
use DateTime;
use DateTimeInterface;
use ReflectionClass;
use Stringable;
use UnitEnum;

trait IsStringValueObject
{
    private string $internal;
    public function __construct(string|int|float|bool|Stringable $input)
    {
        $input = $this->convert((string) $input);
        static::validate($input);
        $this->internal = $input;
    }

    public static function fromNative(mixed $input): self
    {
        if (gettype($input) == 'boolean') {
            $input = $input ? 'true' : 'false';
        }
        if ($input instanceof ValueObjectInterface) {
            $input = $input->toNative();
        }
        if ($input instanceof DateTimeInterface) {
            $input = $input->format(DateTime::ATOM);
        }
        if ($input instanceof UnitEnum) {
            $input = $input->value;
        }
        if (is_array($input)) {
            throw new InvalidTypeException($input, (new ReflectionClass(self::class))->getShortName());
        }
        if (is_object($input) && !$input instanceof Stringable) {
            throw new InvalidTypeException(
                $input,
                (new ReflectionClass(self::class))->getShortName()
            );
        }
        $class = new ReflectionClass(static::class);
        if (!$class->isInstantiable()) {
            self::validate((string) $input);
            return new self((string) $input);
        }
        $refl = (new ReflectionClass(static::class));
        $constructor = $refl->getConstructor();
        $arguments = $constructor?->getParameters() ?? [];
        if (count($arguments) === 1) {
            $argumentType = reset($arguments)->getType() ?? ReflectionTypeFactory::createReflectionType('mixed');
            if (TypeUtils::matchesType($argumentType, $input)) {
                return new static($input);
            }
            return new static(
                ConverterUtils::dynamicCast(
                    $input,
                    $argumentType
                )
            );
        }
        $instance = $refl->newInstanceWithoutConstructor();
        $instance->internal = (string) $input;

        return $instance;
    }
    public function toNative(): string
    {
        return $this->internal;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function jsonSerialize(): string
    {
        return $this->toNative();
    }
    
    public static function validate(string $input): void
    {
    }

    protected function convert(string $input): string
    {
        return $input;
    }
}
