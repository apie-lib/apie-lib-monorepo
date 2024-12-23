<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\Core\Attributes\FakeMethod;
use Apie\CountryAndPhoneNumber\Concerns\CanCreateRandomPhoneNumber;
use PrinsFrank\Standards\Country\CountryAlpha2;

#[FakeMethod('createRandomInstance')]
class GermanPhoneNumber extends PhoneNumber
{
    use CanCreateRandomPhoneNumber;

    public static function fromCountry(): CountryAlpha2
    {
        return CountryAlpha2::Germany;
    }
}
