<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class Oauth2SecuritySchemeRequiredFieldsMissing extends ApieException
{
    public function __construct()
    {
        parent::__construct(
            500,
            'An Oauth2 security scheme requires the fields "flows" to be filled in.'
        );
    }
}
