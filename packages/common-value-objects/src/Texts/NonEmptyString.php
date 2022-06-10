<?php
namespace Apie\CommonValueObjects\Texts;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;

class NonEmptyString implements ValueObjectInterface
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
}
