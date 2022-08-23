<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\CountryAndPhoneNumber\Concerns\CanCreateRandomPhoneNumber;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

#[FakeMethod('createRandomInstance')]
class GermanPhoneNumber extends PhoneNumber
{
    use CanCreateRandomPhoneNumber;

    public static function fromCountry(): ISO3166_1_Alpha_2
    {
        return ISO3166_1_Alpha_2::Germany;
    }
}
