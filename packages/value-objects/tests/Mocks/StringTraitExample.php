<?php


namespace Apie\Tests\ValueObjects\Mocks;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class StringTraitExample implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return !empty($value);
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }
}
