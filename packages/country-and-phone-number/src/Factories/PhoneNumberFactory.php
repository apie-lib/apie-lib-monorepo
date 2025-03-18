<?php
namespace Apie\CountryAndPhoneNumber\Factories;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\CountryAndPhoneNumber\BritishPhoneNumber;
use Apie\CountryAndPhoneNumber\CanadianPhoneNumber;
use Apie\CountryAndPhoneNumber\ChinesePhoneNumber;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\CountryAndPhoneNumber\Exceptions\CountryAlreadyRegistered;
use Apie\CountryAndPhoneNumber\FrenchPhoneNumber;
use Apie\CountryAndPhoneNumber\GermanPhoneNumber;
use Apie\CountryAndPhoneNumber\JapanesePhoneNumber;
use Apie\CountryAndPhoneNumber\MexicanPhoneNumber;
use Apie\CountryAndPhoneNumber\PhoneNumber;
use Apie\CountryAndPhoneNumber\USPhoneNumber;
use PrinsFrank\Standards\Country\CountryAlpha2;

final class PhoneNumberFactory
{
    /**
     * @var array<string, class-string<PhoneNumber>>
     */
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
    private static function getClass(CountryAlpha2 $countryEnum): string
    {
        $country = $countryEnum->value;
        if (!isset(self::$instantiatedClasses[$country])) {
            // this code is evil....
            $class = eval('return new class("", $countryEnum) extends \Apie\CountryAndPhoneNumber\PhoneNumber {
                static private \PrinsFrank\Standards\Country\CountryAlpha2 $country;
                static private bool $ignored = true;

                public function __construct(string $input, ?\PrinsFrank\Standards\Country\CountryAlpha2 $country = null)
                {
                    if (self::$ignored && empty($input) && $country !== null) {
                        self::$country = $country;
                        self::$ignored = false;
                        return;
                    }
                    parent::__construct($input);
                }

                static public function fromCountry(): \PrinsFrank\Standards\Country\CountryAlpha2 {
                    return self::$country;
                }
            };');
            self::$instantiatedClasses[$country] = get_class($class);
        }
        return self::$instantiatedClasses[$country];
    }

    public static function register(string $class): void
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

    public static function createFrom(string $phoneNumber, CountryAlpha2 $countryEnum): PhoneNumber
    {
        $class = self::getClass($countryEnum);
        return new $class($phoneNumber);
    }
}
