<?php
namespace Apie\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\IsStringValueObject;

trait StaticDataValueObject
{
    use IsStringValueObject;

    abstract protected static function getData(): array;

    abstract protected static function requiresActive(): bool;

    protected function getFieldValue(string $fieldName): mixed
    {
        return static::getData()[strtolower($this->internal)][$fieldName] ?? null;
    }

    public static function validate(string $input): void
    {
        $input = strtolower($input);
        $data = static::getData();
        if (!isset($data[$input])) {
            throw new InvalidStringForValueObjectException(
                $input,
                (new \ReflectionClass(static::class))
            );
        };
        $active = $data[$input]['Active'] ?? false;
        $deprecated = !empty($data[$input]['Deprecated'] ?? null);
        if (((!$active || $deprecated) && self::requiresActive())) {
            throw new InvalidStringForValueObjectException(
                $input,
                (new \ReflectionClass(static::class))
            );
        }
    }
}