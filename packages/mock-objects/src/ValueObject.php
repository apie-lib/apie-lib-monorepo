<?php
namespace Apie\MockObjects;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class ValueObject implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return $value !== '';
    }

    protected function sanitizeValue(string $value): string
    {
        return $value;
    }
}
