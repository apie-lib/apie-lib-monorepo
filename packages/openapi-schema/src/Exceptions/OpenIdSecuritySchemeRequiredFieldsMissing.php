<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class OpenIdSecuritySchemeRequiredFieldsMissing extends ApieException
{
    public function __construct()
    {
        parent::__construct(
            500,
            'An OpenID security scheme requires the fields "openIdConnectUrl" to be filled in.'
        );
    }
}
