<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeMethod;
use Apie\CountryAndPhoneNumber\Concerns\CanCreateRandomPhoneNumber;
use PrinsFrank\Standards\Country\CountryAlpha2;

#[FakeMethod('createRandomInstance')]
#[Description('A phone number valid in Japan in national format or E164 format')]
class JapanesePhoneNumber extends PhoneNumber
{
    use CanCreateRandomPhoneNumber;

    public static function fromCountry(): CountryAlpha2
    {
        return CountryAlpha2::Japan;
    }
}
