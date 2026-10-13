<?php
namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\CmsSingleInput;
use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
#[CmsSingleInput(['hostname', 'text'])]
#[Description('Represents a hostname, such as a domain name or subdomain')]
#[ExampleValue('example.com')]
class Hostname implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function createRandom(Generator $generator): self
    {
        return new static($generator->domainName());
    }

    protected function convert(string $input): string
    {
        return strtolower(trim($input));
    }

    public static function validate(string $input): void
    {
        if ($input === '' || filter_var($input, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }
}
