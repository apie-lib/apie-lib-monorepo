<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\ValueObjects\Concerns\IndexesWords;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;

#[FakeMethod('createRandom')]
#[ProvideIndex('getIndexes')]
class CompanyName implements HasRegexValueObjectInterface
{
    use IndexesWords;
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^\w[\w\-\s\'`"‘’“”‟,]*$/u';
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function createRandom(Generator $generator): self
    {
        return new static($generator->company());
    }
}
