<?php
namespace Apie\CommonValueObjects\Texts;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;

class SmallDatabaseText implements ValueObjectInterface
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
}
