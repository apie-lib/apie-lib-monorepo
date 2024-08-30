<?php
namespace Apie\Maker\ValueObjects;

use Apie\ApieCommonPlugin\ObjectProviderFactory;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Maker\Concerns\IsClassNameReference;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
final class VendorValueObject implements HasRegexValueObjectInterface, LimitedOptionsInterface
{
    use IsClassNameReference;
    use IsStringValueObject;

    private static StringSet $options;

    public static function validate(string $input): void
    {
        if (!preg_match(static::getRegularExpression(), $input)) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
        if ($input === __CLASS__) {
            return;
        }
        $objects = self::getOptions();
        if (!empty($objects) && !isset($objects[$input])) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
    }

    public static function getOptions(): StringSet
    {
        if (!isset(self::$options)) {
            self::$options = new StringSet([__CLASS__, ...ObjectProviderFactory::create()->getAvailableValueObjects()]);
        }
        return self::$options;
    }

    public static function createRandom(Generator $factory): self
    {
        return new self($factory->randomElement(self::getOptions()->toArray()));
    }
}
