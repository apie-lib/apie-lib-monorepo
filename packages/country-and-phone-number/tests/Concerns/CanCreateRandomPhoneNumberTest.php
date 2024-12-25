<?php
namespace Apie\Tests\CountryAndPhoneNumber\Concerns;

use Apie\CountryAndPhoneNumber\Concerns\CanCreateRandomPhoneNumber;
use libphonenumber\PhoneNumberUtil;
use LogicException;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\CountryAlpha2;

class CanCreateRandomPhoneNumberTest extends TestCase
{
    use CanCreateRandomPhoneNumber;

    private static CountryAlpha2 $country = CountryAlpha2::Netherlands;

    protected function tearDown(): void
    {
        self::$country = CountryAlpha2::Netherlands;
    }

    public static function fromCountry(): CountryAlpha2
    {
        return self::$country;
    }
    
    protected static function getUtil(): PhoneNumberUtil
    {
        return PhoneNumberUtil::getInstance();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_error_if_libphonenumber_has_no_example_phonenumber()
    {
        self::$country = CountryAlpha2::Antarctica;
        $this->expectException(LogicException::class);
        self::createRandomInstance();
    }
}
