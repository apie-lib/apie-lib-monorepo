<?php

namespace Apie\Tests\ValueObjects\Mocks;

use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

class StringEnumTraitExample implements ValueObjectInterface
{
    use StringEnumTrait;

    const A = 'OPTION_A';

    const B = 'OPTION_B';
}
