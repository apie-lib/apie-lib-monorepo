<?php
namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\CountWords\WordCounter;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;
use Faker\Generator;
use Stringable;

/**
 * Represents a full name: gender + first name + last name.
 */
#[FakeMethod('createRandom')]
#[ProvideIndex('getIndexes')]
class FullName implements ValueObjectInterface, Stringable
{
    use CompositeValueObject;

    public function __construct(
        private Gender $gender,
        private FirstName $firstName,
        private LastName $lastName
    ) {
    }

    public function __toString(): string
    {
        return $this->gender->getSalutation() . ' ' . $this->firstName . ' '  . $this->lastName;
    }

    /**
     * @return array<string, int>
     */
    public function getIndexes(): array
    {
        return WordCounter::countFromString($this->__toString());
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
