<?php


namespace Apie\ValueObjects;

use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use LogicException;
use ReflectionClass;

trait StringTrait
{
    private $value;

    final public static function fromNative($value)
    {
        return new self((string) $value);
    }

    final public function __construct(string $value)
    {
        if (!$this->validValue($value)) {
            throw new InvalidValueForValueObjectException($value, __CLASS__);
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