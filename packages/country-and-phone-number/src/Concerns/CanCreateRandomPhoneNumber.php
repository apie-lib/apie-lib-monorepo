<?php
namespace Apie\CountryAndPhoneNumber\Concerns;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use LogicException;
use PrinsFrank\Standards\Country\CountryAlpha2;
use RegRev\RegRev;

trait CanCreateRandomPhoneNumber
{
    abstract public static function fromCountry(): CountryAlpha2;
    abstract protected static function getUtil(): PhoneNumberUtil;

    public static function createRandomInstance(): static
    {
        $phoneNumberUtil = self::getUtil();
        $country = self::fromCountry();

        $metadata = $phoneNumberUtil->getMetadataForRegion($country->value);
        if ($metadata) {
            $pattern = $metadata->getFixedLine()->getNationalNumberPattern();
            if ($pattern) {
                return new static(RegRev::generate('^' . str_replace('?:', '', $pattern) . '$'));
            }
        }

        $phoneNumberObject = $phoneNumberUtil->getExampleNumber($country->value);
        if ($phoneNumberObject) {
            return new static($phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164));
        }
        throw new LogicException('I have no logic to create a fake phone number for ' . __CLASS__);
    }
}
