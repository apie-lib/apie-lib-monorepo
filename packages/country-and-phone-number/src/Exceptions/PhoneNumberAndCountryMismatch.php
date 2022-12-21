<?php
namespace Apie\CountryAndPhoneNumber\Exceptions;

use Apie\Core\Exceptions\ApieException;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;
use Throwable;

class PhoneNumberAndCountryMismatch extends ApieException
{
    public function __construct(ISO3166_1_Alpha_2 $country, ?ISO3166_1_Alpha_2 $phoneCountry, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Phone number and country are not from the same country. Country is "%s", phone number is "%s"',
                $country->value,
                $phoneCountry ? $phoneCountry->value : '(unknown)'
            ),
            0,
            $previous
        );
    }
}
