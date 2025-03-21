<?php
namespace Apie\CountryAndPhoneNumber\Fields;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Fields\FieldInterface;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\Utils;
use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\CountryAndPhoneNumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhoneNumber\InternationalPhoneNumber;
use Apie\CountryAndPhoneNumber\PhoneNumber;
use Exception;
use ReflectionProperty;

final class DynamicPhoneNumberProperty implements FieldInterface
{
    private ReflectionProperty $property;
    private ReflectionProperty $countryProperty;

    public function __construct()
    {
        $this->property = new ReflectionProperty(CountryAndPhoneNumber::class, 'phoneNumber');
        $this->countryProperty = new ReflectionProperty(CountryAndPhoneNumber::class, 'country');
        $this->property->setAccessible(true);
        $this->countryProperty->setAccessible(true);
    }

    public function getTypehint(): string
    {
        return PhoneNumber::class;
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function fromNative(ValueObjectInterface $instance, mixed $value): void
    {
        // validation of country property is hit, ignore this error
        if (!$this->countryProperty->isInitialized($instance)) {
            return;
        }
        $country = $this->countryProperty->getValue($instance);
        try {
            $phoneNumber = PhoneNumberFactory::createFrom($value, $country);
        } catch (InvalidStringForValueObjectException $error) {
            $phoneNumberCountry = null;
            try {
                $phoneNumberCountry = InternationalPhoneNumber::fromNative($value)->toPhoneNumber()->fromCountry();
            } catch (Exception $ignored) {
                // fallthrough
            }

            throw new PhoneNumberAndCountryMismatch($country, $phoneNumberCountry, $error);
        }
        self::fillField($instance, $phoneNumber);
    }

    public function fillField(ValueObjectInterface $instance, mixed $value): void
    {
        $this->property->setValue($instance, $value);
    }

    public function fillMissingField(ValueObjectInterface $instance): void
    {
        throw new InvalidTypeException('(missing value)', $this->getTypehint());
    }

    public function isInitialized(ValueObjectInterface $instance): bool
    {
        return $this->property->isInitialized($instance);
    }

    public function getValue(ValueObjectInterface $instance): mixed
    {
        return $this->property->getValue($instance);
    }

    public function toNative(ValueObjectInterface $instance): string
    {
        $value = $this->getValue($instance);
        return Utils::toNative($value);
    }
}
