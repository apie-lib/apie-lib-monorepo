<?php
namespace Apie\MockObjects\ValueObjects;

use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class MockValueObjectWithStringTrait implements ValueObjectInterface
{
    use StringTrait;

    public $validValueRetrieved;

    protected function validValue(string $value): bool
    {
        $this->validValueRetrieved = $value;
        return $value !== 'blha';
    }

    protected function sanitizeValue(string $value): string
    {
        return strtoupper($value);
    }
}
