<?php
namespace Apie\CommonValueObjects\Identifiers;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;

class KebabCaseSlug implements ValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^[a-z][a-z0-9]*(\-[a-z][a-z0-9]+)$/';
    }
}
