<?php
namespace Apie\CommonValueObjects;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * Value object for a url.
 */
final class Url implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    protected function sanitizeValue(string $value): string
    {
        return $value;
    }
}