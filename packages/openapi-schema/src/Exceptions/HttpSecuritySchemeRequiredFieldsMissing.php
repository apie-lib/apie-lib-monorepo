<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class HttpSecuritySchemeRequiredFieldsMissing extends ApieException
{
    public function __construct()
    {
        parent::__construct(
            500,
            'A http security scheme requires the fields "scheme" to be filled in.'
        );
    }
}
