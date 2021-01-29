<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class DefaultValueNotInEnum extends ApieException
{
    public function __construct(string $defaultValue, string... $enums)
    {
        parent::__construct(500, sprintf(
            'The default value "%s" is not found in the enum. It should be one of %s',
            $defaultValue,
            trim(json_encode($enums), '[]')
        ));
    }
}