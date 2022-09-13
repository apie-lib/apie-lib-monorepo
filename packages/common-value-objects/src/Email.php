<?php
namespace Apie\CommonValueObjects;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Faker\Generator;
use ReflectionClass;
use Stringable;

/**
 * Represents a full name: gender + first name + last name.
 */
#[FakeMethod('createRandom')]
class Email implements StringValueObjectInterface
{
    use IsStringValueObject;


    public static function createRandom(Generator $generator): self
    {
        return new Email($generator->email());
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function validate(string $input): void
    {
        $validator = new EmailValidator();
        if (!$validator->isValid("example@example.com", new RFCValidation())) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }
}