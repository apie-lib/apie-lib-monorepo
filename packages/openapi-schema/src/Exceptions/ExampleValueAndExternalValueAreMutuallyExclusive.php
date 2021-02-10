<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class ExampleValueAndExternalValueAreMutuallyExclusive extends ApieException
{
    public function __construct()
    {
        parent::__construct(
            400,
            'Examples value and external value are mutually exclusive'
        );
    }
}
