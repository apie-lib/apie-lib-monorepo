<?php
namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\CmsSingleInput;
use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
#[CmsSingleInput(['local-part', 'text'])]
#[Description('Represents the local part of an e-mail address')]
class EmailLocalPart implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function createRandom(Generator $generator): self
    {
        return new static(str_replace(' ', '', $generator->userName()));
    }

    public static function validate(string $input): void
    {
        if ($input === '' || preg_match('/^(?=.{1,64}$)[A-Za-z0-9.!#$%&\'*+\/?^_`{|}~-]+$/', $input) !== 1) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }
}
