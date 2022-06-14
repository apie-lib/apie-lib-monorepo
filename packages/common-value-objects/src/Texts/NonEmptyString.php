<?php
namespace Apie\CommonValueObjects\Texts;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;

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
}
