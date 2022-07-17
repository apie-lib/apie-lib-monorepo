<?php
namespace Apie\CountryAndPhonenumber;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\CompositeValueObjects\Fields\FromProperty;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\CountryAndPhonenumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhonenumber\Fields\DynamicPhoneNumberProperty;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;
use ReflectionProperty;

final class CountryAndPhoneNumber implements ValueObjectInterface
{
    use CompositeValueObject;

    public function __construct(private ISO3166_1_Alpha_2 $country, private PhoneNumber $phoneNumber)
    {
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
}
