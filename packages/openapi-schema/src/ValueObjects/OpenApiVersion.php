<?php


namespace Apie\OpenapiSchema\ValueObjects;

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
        return preg_match('/^3\.[01]\.\d+$/', $value) ? true : false;
    }

    protected function sanitizeValue(string $value): string
    {
        return $value;
    }
}
