<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;

#[FakeMethod('createRandom')]
class NonEmptyString implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^.+$/';
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function createRandom(Generator $generator): self
    {
        return new NonEmptyString($generator->realText(1024));
    }
}
