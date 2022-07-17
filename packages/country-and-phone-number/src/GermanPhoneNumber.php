<?php
namespace Apie\CountryAndPhonenumber;

use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class GermanPhoneNumber extends PhoneNumber
{
    public static function fromCountry(): ISO3166_1_Alpha_2
    {
        return ISO3166_1_Alpha_2::Germany;
    }
}
