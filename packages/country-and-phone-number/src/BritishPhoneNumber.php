<?php
namespace Apie\CountryAndPhonenumber;

use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class BritishPhoneNumber extends PhoneNumber
{
    public static function fromCountry(): ISO3166_1_Alpha_2
    {
        return ISO3166_1_Alpha_2::United_Kingdom_of_Great_Britain_and_Northern_Ireland_the;
    }
}
