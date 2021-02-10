<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class OAuthRequiresAuthorizationUrl extends ApieException
{
    public function __construct()
    {
        parent::__construct(400, 'Implicit oauth flow requires an authorization url');
    }
}
