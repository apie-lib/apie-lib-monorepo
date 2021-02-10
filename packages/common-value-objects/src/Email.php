<?php


namespace Apie\CommonValueObjects;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class Email implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }
}
