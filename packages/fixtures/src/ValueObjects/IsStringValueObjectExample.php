<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use ReflectionClass;

#[Description("An example class of a string value.")]
class IsStringValueObjectExample implements ValueObjectInterface
{
    use IsStringValueObject;

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function validate(string $input): void
    {
        if (empty($input)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(self::class));
        }
    }
}
