<?php
namespace Apie\CountryAndPhoneNumber\Fields;

use Apie\CompositeValueObjects\Fields\FieldInterface;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\Utils;
use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\CountryAndPhoneNumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhoneNumber\PhoneNumber;
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

    public function fromNative(ValueObjectInterface $instance, mixed $value)
    {
        $country = $this->countryProperty->getValue($instance);
        try {
            $phoneNumber = PhoneNumberFactory::createFrom($value, $country);
        } catch (InvalidStringForValueObjectException $error) {
            throw new PhoneNumberAndCountryMismatch($country, null, $error);
        }
        self::fillField($instance, $phoneNumber);
    }

    public function fillField(ValueObjectInterface $instance, mixed $value)
    {
        $this->property->setValue($instance, $value);
    }

    public function fillMissingField(ValueObjectInterface $instance)
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
