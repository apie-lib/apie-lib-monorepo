<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Faker\Generator;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PrinsFrank\Standards\Country\CountryAlpha2;
use ReflectionClass;

/**
 * International phone number in E164 format.
 */
#[FakeMethod('createRandom')]
final class InternationalPhoneNumber implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        if (!$phoneUtil->isPossibleNumber($input)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }

    protected function convert(string $input): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phone = $phoneUtil->parse($input);
        return $phoneUtil->format($phone, PhoneNumberFormat::E164);
    }

    public function toPhoneNumber(): PhoneNumber
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phone = $phoneUtil->parse($this->internal);
        return PhoneNumberFactory::createFrom(
            $this->internal,
            CountryAlpha2::from($phoneUtil->getRegionCodeForNumber($phone))
        );
    }

    public static function createRandom(Generator $generator): self
    {
        $phoneNumber = '';
        do {
            $country = $generator->randomElement(CountryAlpha2::cases());
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneNumberUtil->getExampleNumber($country->value);
            if ($phoneNumberObject) {
                $phoneNumber = $phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
            }
        } while ($phoneNumber === '');
        return new InternationalPhoneNumber($phoneNumber);
    }
}
