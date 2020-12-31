<?php
namespace Apie\OpenapiSchema;

final class Constants
{
    const VALID_KEY_REGEX = '/^[a-zA-Z0-9\.\-_]+$/';

    const VALID_EXPRESSION_REGEX = '/^{[^{]+}$/';

    const OPENAPI_VERSION = '3.1.0';

    private function __construct()
    {
    }
}