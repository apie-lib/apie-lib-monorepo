<?php


namespace Apie\ValueObjects;

use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;

trait StringTrait
{
    private $value;

    final public static function fromNative($value)
    {
        if ($value instanceof ValueObjectInterface) {
            $value = $value->toNative();
        }
        if (is_array($value)) {
            throw new InvalidValueForValueObjectException($value, static::class);
        }
        return new self((string) $value);
    }

    final public function __construct(string $value)
    {
        if (!$this->validValue($value)) {
            throw new InvalidValueForValueObjectException($value, static::class);
        }
        $this->value = $this->sanitizeValue($value);
    }

    abstract protected function validValue(string $value): bool;

    abstract protected function sanitizeValue(string $value): string;

    final public function toNative()
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return $this->toNative();
    }
}
