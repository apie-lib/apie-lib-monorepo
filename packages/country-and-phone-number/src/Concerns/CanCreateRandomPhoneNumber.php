<?php
namespace Apie\CountryAndPhoneNumber\Concerns;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use LogicException;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

trait CanCreateRandomPhoneNumber
{
    abstract public static function fromCountry(): ISO3166_1_Alpha_2;
    abstract protected static function getUtil(): PhoneNumberUtil;

    public static function createRandomInstance(): static
    {
        $phoneNumberUtil = self::getUtil();
        $country = self::fromCountry();

        $phoneNumberObject = $phoneNumberUtil->getExampleNumber($country->value);
        if ($phoneNumberObject) {
            return new static($phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164));
        }
        throw new LogicException('I have no logic to create a fake phone number for ' . __CLASS__);
    }
}
