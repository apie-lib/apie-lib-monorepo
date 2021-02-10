<?php


namespace Apie\OpenapiSchema\ValueObjects;

use Apie\ValueObjects\StringEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * Enum for security scheme type column.
 */
class SecuritySchemeType implements ValueObjectInterface
{
    use StringEnumTrait;

    const API_KEY = 'apiKey';

    const HTTP = 'http';

    const OAUTH2 = 'oauth2';

    const OPEN_ID_CONNECT = 'openIdConnect';
}
