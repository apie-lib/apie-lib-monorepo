<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

use Apie\ObjectAccessNormalizer\Utils;
use RuntimeException;

class ValueObject
{
    public function __construct($value)
    {
        throw new RuntimeException(Utils::toString($value));
    }
}
