<?php
namespace Apie\CommonValueObjects;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;
use Faker\Generator;
use Stringable;

/**
 * Represents a full name: gender + first name + last name.
 */
#[FakeMethod('createRandom')]
class FullName implements ValueObjectInterface, Stringable
{
    use CompositeValueObject;

    public function __construct(
        private readonly Gender $gender,
        private readonly FirstName $firstName,
        private readonly LastName $lastName
    ) {
    }

    public function __toString(): string
    {
        return $this->gender->getSalutation() . ' ' . $this->firstName . ' '  . $this->lastName;
    }

    public static function createRandom(Generator $generator): self
    {
        $gender = $generator->randomElement(['male', 'female']);

        return new self(
            $gender === 'male' ? Gender::MALE : Gender::FEMALE,
            new FirstName($generator->firstName($gender)),
            new LastName($generator->lastName())
        );
    }
}
