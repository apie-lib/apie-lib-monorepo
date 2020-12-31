<?php


namespace Apie\OpenapiSchema\ValueObjects;


use Apie\OpenapiSchema\Constants;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * OpenAPI version string value object.
 */
final class OpenApiVersion implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return $value === Constants::OPENAPI_VERSION;
    }

    protected function sanitizeValue(string $value): string
    {
        return Constants::OPENAPI_VERSION;
    }
}