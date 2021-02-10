<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class ApiKeySecuritySchemeRequiredFieldsMissing extends ApieException
{
    public function __construct()
    {
        parent::__construct(
            500,
            'A api key security scheme requires the fields "name" and "in" to be filled in.'
        );
    }
}
