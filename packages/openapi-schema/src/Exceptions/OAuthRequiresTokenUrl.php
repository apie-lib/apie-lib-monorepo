<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class OAuthRequiresTokenUrl extends ApieException
{
    public function __construct()
    {
        parent::__construct(400, 'Password and client credentials oauth flow requires a token url');
    }
}
