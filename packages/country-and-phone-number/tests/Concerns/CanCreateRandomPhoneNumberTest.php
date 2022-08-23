<?php
namespace Apie\Tests\CountryAndPhoneNumber\Concerns;

use Apie\CountryAndPhoneNumber\Concerns\CanCreateRandomPhoneNumber;
use libphonenumber\PhoneNumberUtil;
use LogicException;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class CanCreateRandomPhoneNumberTest extends TestCase
{
    use CanCreateRandomPhoneNumber;

    private static ISO3166_1_Alpha_2 $country = ISO3166_1_Alpha_2::Netherlands_the;

    protected function tearDown(): void
    {
        self::$country = ISO3166_1_Alpha_2::Netherlands_the;
    }

    public static function fromCountry(): ISO3166_1_Alpha_2
    {
        return self::$country;
    }
    
    protected static function getUtil(): PhoneNumberUtil
    {
        return PhoneNumberUtil::getInstance();
    }

    /**
     * @test
     */
    public function it_throws_error_if_libphonenumber_has_no_example_phonenumber()
    {
        self::$country = ISO3166_1_Alpha_2::Antarctica;
        $this->expectException(LogicException::class);
        self::createRandomInstance();
    }
}
