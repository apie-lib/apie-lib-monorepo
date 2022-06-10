<?php
namespace Apie\CommonValueObjects\Identifiers;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;

class Uuid implements ValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}$/';
    }
}
