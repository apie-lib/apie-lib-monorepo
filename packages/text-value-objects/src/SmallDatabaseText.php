<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;

#[FakeMethod('createRandom')]
class SmallDatabaseText implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^.{0,255}$/';
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function createRandom(Generator $generator): self
    {
        return new SmallDatabaseText($generator->realText(80));
    }
}
