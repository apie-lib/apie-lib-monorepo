<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

use RuntimeException;
use Apie\ObjectAccessNormalizer\Utils;

class ValueObject
{
    public function __construct($value) {
        throw new RuntimeException(Utils::toString($value));
    }
}
