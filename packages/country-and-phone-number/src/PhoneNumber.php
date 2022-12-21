<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Faker\Generator;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber as LibPhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;
use ReflectionClass;

#[FakeMethod('createRandom')]
abstract class PhoneNumber implements StringValueObjectInterface
{
    use IsStringValueObject;

    private static PhoneNumberUtil $util;

    private LibPhoneNumber $phoneNumber;

    abstract public static function fromCountry(): ISO3166_1_Alpha_2;

    final protected static function getUtil(): PhoneNumberUtil
    {
        if (!isset(self::$util)) {
            self::$util = PhoneNumberUtil::getInstance();
        }
        return self::$util;
    }

    final protected function convert(string $input): string
    {
        $phoneNumberUtil = self::getUtil();
        try {
            $this->phoneNumber = $phoneNumberUtil->parse($input, static::fromCountry()->value);
        } catch (NumberParseException $error) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(static::class), $error);
        }
        return $phoneNumberUtil->format($this->phoneNumber, PhoneNumberFormat::E164);
    }

    public static function validate(string $input): void
    {
        $phoneNumberUtil = self::getUtil();
        try {
            $phoneNumber = $phoneNumberUtil->parse($input, static::class === PhoneNumber::class ? null : static::fromCountry()->value);
        } catch (NumberParseException $error) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(static::class), $error);
        }
        if (!$phoneNumberUtil->isValidNumberForRegion($phoneNumber, static::fromCountry()->value)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(static::class));
        }
    }

    public static function createRandom(Generator $generator): self
    {
        $phoneNumber = '';
        do {
            $country = $generator->randomElement(ISO3166_1_Alpha_2::cases());
            $phoneNumberUtil = self::getUtil();
            $phoneNumberObject = $phoneNumberUtil->getExampleNumber($country->value);
            if ($phoneNumberObject) {
                $phoneNumber = $phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
            }
        } while ($phoneNumber === '');
        return PhoneNumberFactory::createFrom(
            $phoneNumber,
            $country
        );
    }
}
