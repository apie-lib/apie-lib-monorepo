<?php


namespace Apie\CommonValueObjects;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class Identifier implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9-_]*$/', $value) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }
}
