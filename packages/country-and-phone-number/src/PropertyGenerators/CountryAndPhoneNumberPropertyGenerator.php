<?php
namespace Apie\CountryAndPhoneNumber\PropertyGenerators;

use Apie\Core\Persistence\Fields\FieldReference;
use Apie\Core\Persistence\PersistenceFieldInterface;
use Apie\Core\Persistence\PersistenceTableInterface;
use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\DoctrineEntityConverter\PropertyGenerators\FieldReferencePropertyGenerator;
use ReflectionNamedType;
use ReflectionProperty;

class CountryAndPhoneNumberPropertyGenerator extends FieldReferencePropertyGenerator
{
    protected function supportsProperty(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): bool {
        if ($field instanceof FieldReference) {
            $type = $field->getProperty()->getType();
            return $type instanceof ReflectionNamedType && $type->getName() === CountryAndPhoneNumber::class;
        }

        return false;
    }

    protected function generateFromCodeConversion(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): string {
        assert($field instanceof FieldReference);
        return sprintf(
            '%s::createFrom($raw)',
            $field->getTableReference()
        );
    }

    protected function generateInjectConversion(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): string {
        assert($field instanceof FieldReference);
        return sprintf(
            '\\%s::fromNative(["country" => $tmp->apie_country, "phoneNumber" => $tmp->apie_phone_number])',
            CountryAndPhoneNumber::class
        );
    }
}
