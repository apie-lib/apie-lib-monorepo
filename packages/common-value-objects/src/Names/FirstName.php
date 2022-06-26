<?php
namespace Apie\CommonValueObjects\Names;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;

class FirstName implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^\w[\w\-\s\'`"‘’“”‟]*$/u';
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }
}
