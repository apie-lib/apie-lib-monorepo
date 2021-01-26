<?php
namespace Apie\Tests\ObjectAccessNormalizer\Mocks\TestCase1;

use RuntimeException;

class ValidationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('A validation error occured');
    }
}
