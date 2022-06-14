<?php
namespace Apie\CommonValueObjects\Texts;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;

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
}
