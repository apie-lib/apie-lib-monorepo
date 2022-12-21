<?php
namespace Apie\CountryAndPhoneNumber;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\CompositeValueObjects\CompositeWithOwnValidation;
use Apie\CompositeValueObjects\Fields\FieldInterface;
use Apie\CompositeValueObjects\Fields\FromProperty;
use Apie\Core\Attributes\FakeMethod;
use Apie\CountryAndPhoneNumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhoneNumber\Fields\DynamicPhoneNumberProperty;
use Apie\Serializer\Exceptions\ValidationException;
use Faker\Generator;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;
use ReflectionProperty;

#[FakeMethod('createRandom')]
final class CountryAndPhoneNumber implements CompositeWithOwnValidation
{
    use CompositeValueObject;

    public function __construct(private ISO3166_1_Alpha_2 $country, private PhoneNumber $phoneNumber)
    {
        $this->validateState();
    }

    /**
     * @return array<string, FieldInterface>
     */
    public static function getFields(): array
    {
        return [
            'country' => new FromProperty(new ReflectionProperty(CountryAndPhoneNumber::class, 'country')),
            'phoneNumber' => new DynamicPhoneNumberProperty(),
        ];
    }

    private function validateState(): void
    {
        if ($this->country !== $this->phoneNumber->fromCountry()) {
            throw ValidationException::createFromArray(
                [
                    'phoneNumber'  => new PhoneNumberAndCountryMismatch(
                        $this->country,
                        $this->phoneNumber->fromCountry()
                    )
                ]
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
