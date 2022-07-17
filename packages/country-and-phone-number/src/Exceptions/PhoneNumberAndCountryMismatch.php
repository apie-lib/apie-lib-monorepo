<?php
namespace Apie\CountryAndPhonenumber\Exceptions;

use Apie\Core\Exceptions\ApieException;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class PhoneNumberAndCountryMismatch extends ApieException
{
    public function __construct(ISO3166_1_Alpha_2 $country, ISO3166_1_Alpha_2 $phoneCountry)
    {
        parent::__construct(
            sprintf(
                'Phone number and country are not from the same country. Country is "%s", phone country is "%s"',
                $country->value,
                $phoneCountry->value
            )
        );
    }
}
