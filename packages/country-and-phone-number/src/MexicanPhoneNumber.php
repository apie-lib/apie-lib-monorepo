<?php
namespace Apie\CountryAndPhoneNumber;

use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class MexicanPhoneNumber extends PhoneNumber
{
    public static function fromCountry(): ISO3166_1_Alpha_2
    {
        return ISO3166_1_Alpha_2::Mexico;
    }
}
