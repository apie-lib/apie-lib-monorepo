<?php
namespace Apie\MockObjects\ValueObjects;

use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

class MockValueObjectWithStringEnumTrait implements ValueObjectInterface
{
    use StringEnumTrait;

    const CONSTANT_A = 'A';

    const CONSTANT_B = 'B';

    const CONSTANT_C = 'A';
}
