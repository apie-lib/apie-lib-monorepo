<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\SchemaMethod;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsPasswordValueObject;

#[SchemaMethod("getOpenapiSchema")]
class StrongPassword implements HasRegexValueObjectInterface
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
        return '!@#$%^&*()-_+.';
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

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function getOpenapiSchema(): array
    {
        return [
            'type' => 'string',
            'format' => 'password',
            'pattern' => StrongPassword::getRegularExpression(),
        ];
    }
}
