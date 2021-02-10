<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

use RuntimeException;

class ClassWithGetterWithErrorAndNoConstructor
{
    public function getPizza(): string
    {
        throw new RuntimeException('Out of pizza');
    }
}
