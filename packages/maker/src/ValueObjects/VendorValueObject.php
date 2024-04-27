<?php
namespace Apie\Maker\ValueObjects;

use Apie\ApieCommonPlugin\ObjectProviderFactory;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Maker\Concerns\IsClassNameReference;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
final class VendorValueObject implements HasRegexValueObjectInterface
{
    use IsClassNameReference;
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        if (!preg_match(static::getRegularExpression(), $input)) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
        $objects = ObjectProviderFactory::create()->getAvailableValueObjects();
        if (!empty($objects) && !in_array($input, $objects)) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
    }

    public static function createRandom(Generator $factory): self
    {
        return new self($factory->randomElement(ObjectProviderFactory::create()->getAvailableValueObjects() ? : [__CLASS__]));
    }
}
