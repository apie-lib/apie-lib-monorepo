<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class ExampleAndExamplesAreMutuallyExclusive extends ApieException
{
    public function __construct()
    {
        parent::__construct(400, 'Example and Examples are mutually exclusive');
    }
}
