<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;

class DuplicateParameterInParameterList extends ApieException
{
    public function __construct(string $name, ParameterIn $in)
    {
        parent::__construct(
            500,
            sprintf(
                'Parameter with name "%s" in "%s" is found as a duplicate in the parameter list',
                $name,
                $in
            )
        );
    }
}
