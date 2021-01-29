<?php


namespace Apie\CommonValueObjects;


use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class KebabCaseString implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return preg_match('/^[a-z]+(-[a-z]+)*$/i', $value) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }
}