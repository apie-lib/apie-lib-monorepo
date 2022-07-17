<?php
namespace Apie\CountryAndPhonenumber\Factories;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\CountryAndPhonenumber\BritishPhoneNumber;
use Apie\CountryAndPhonenumber\CanadianPhoneNumber;
use Apie\CountryAndPhonenumber\ChinesePhoneNumber;
use Apie\CountryAndPhonenumber\DutchPhoneNumber;
use Apie\CountryAndPhonenumber\Exceptions\CountryAlreadyRegistered;
use Apie\CountryAndPhonenumber\FrenchPhoneNumber;
use Apie\CountryAndPhonenumber\GermanPhoneNumber;
use Apie\CountryAndPhonenumber\JapanesePhoneNumber;
use Apie\CountryAndPhonenumber\MexicanPhoneNumber;
use Apie\CountryAndPhonenumber\PhoneNumber;
use Apie\CountryAndPhonenumber\USPhoneNumber;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

final class PhoneNumberFactory
{
    private static array $instantiatedClasses = [
        'CA' => CanadianPhoneNumber::class,
        'CN' => ChinesePhoneNumber::class,
        'DE' => GermanPhoneNumber::class,
        'FR' => FrenchPhoneNumber::class,
        'GB' => BritishPhoneNumber::class,
        'JP' => JapanesePhoneNumber::class,
        'MX' => MexicanPhoneNumber::class,
        'NL' => DutchPhoneNumber::class,
        'US' => USPhoneNumber::class,
    ];

    private function __construct()
    {
    }

    /**
     * @return class-string<PhoneNumber>
     */
    private static function getClass(ISO3166_1_Alpha_2 $countryEnum): string
    {
        $country = $countryEnum->value;
        if (!isset(self::$instantiatedClasses[$country])) {
            // this code is evil....
            $class = eval('return new class("", $countryEnum) extends \Apie\CountryAndPhonenumber\PhoneNumber {
                static private \PrinsFrank\Standards\Country\ISO3166_1_Alpha_2 $country;
                static private bool $ignored = true;

                public function __construct(string $input, ?\PrinsFrank\Standards\Country\ISO3166_1_Alpha_2 $country = null)
                {
                    if (self::$ignored && empty($input) && $country !== null) {
                        self::$country = $country;
                        self::$ignored = false;
                        return;
                    }
                    parent::__construct($input);
                }

                static public function fromCountry(): \PrinsFrank\Standards\Country\ISO3166_1_Alpha_2 {
                    return self::$country;
                }
            };');
            self::$instantiatedClasses[$country] = get_class($class);
        }
        return self::$instantiatedClasses[$country];
    }

    public static function register(string $class)
    {
        if (!is_a($class, PhoneNumber::class, true)) {
            throw new InvalidTypeException($class, PhoneNumber::class);
        }
        $country = $class::fromCountry()->value;
        if (isset(self::$instantiatedClasses[$country])) {
            throw new CountryAlreadyRegistered($country);
        }
        self::$instantiatedClasses[$country] = $class;
    }

    public static function createFrom(string $phoneNumber, ISO3166_1_Alpha_2 $countryEnum): PhoneNumber
    {
        $class = self::getClass($countryEnum);
        return new $class($phoneNumber);
    }
}
