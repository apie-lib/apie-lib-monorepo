<?php

namespace Apie\Tests\ValueObjects\Mocks;

use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

class StringEnumTraitExampleWithDuplicateValue implements ValueObjectInterface
{
    use StringEnumTrait;

    const A = 'OPTION_A';

    const B = 'OPTION_A';

    const C = 'C';

    const D = 'C';
}