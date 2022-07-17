<?php
namespace Apie\CountryAndPhonenumber;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\CompositeValueObjects\Fields\FromProperty;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\CountryAndPhonenumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhonenumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhonenumber\Fields\DynamicPhoneNumberProperty;
use Faker\Generator;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;
use ReflectionProperty;

#[FakeMethod('createRandom')]
final class CountryAndPhoneNumber implements ValueObjectInterface
{
    use CompositeValueObject;

    public function __construct(private ISO3166_1_Alpha_2 $country, private PhoneNumber $phoneNumber)
    {
        $this->validateState();
    }

    public static function getFields(): array
    {
        return [
            'country' => new FromProperty(new ReflectionProperty(CountryAndPhonenumber::class, 'country')),
            'phoneNumber' => new DynamicPhoneNumberProperty(),
        ];
    }

    private function validateState(): void
    {
        if ($this->country !== $this->phoneNumber->fromCountry()) {
            throw new PhoneNumberAndCountryMismatch(
                $this->country,
                $this->phoneNumber->fromCountry()
            );
        }
    }

    public static function createRandom(Generator $generator): self
    {
        $phoneNumber = '';
        do {
            $country = $generator->randomElement(ISO3166_1_Alpha_2::cases());
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneNumberUtil->getExampleNumber($country->value);
            if ($phoneNumberObject) {
                $phoneNumber = $phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
            }
        } while ($phoneNumber === '');
        return new self($country, PhoneNumberFactory::createFrom($phoneNumber, $country));
    }
}
