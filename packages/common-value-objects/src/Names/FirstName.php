<?php
namespace Apie\CommonValueObjects\Names;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;

class FirstName implements ValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^\w[\w\-\s\'"‘’“”‟]*$/u';
    }
}
