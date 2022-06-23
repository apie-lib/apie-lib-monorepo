<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsPasswordValueObject;

class Password implements HasRegexValueObjectInterface
{
    use IsPasswordValueObject;

    public static function getMinLength(): int
    {
        return 6;
    }

    public static function getMaxLength(): int
    {
        return 42;
    }

    public static function getAllowedSpecialCharacters(): string
    {
        return '!@#$%^&*-_+.';
    }

    public static function getMinSpecialCharacters(): int
    {
        return 1;
    }

    public static function getMinDigits(): int
    {
        return 1;
    }

    public static function getMinLowercase(): int
    {
        return 1;
    }

    public static function getMinUppercase(): int
    {
        return 1;
    }
}
