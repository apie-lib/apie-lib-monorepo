<?php


namespace Apie\OpenapiSchema\Exceptions;

use Apie\Core\Exceptions\ApieException;

class MissingPlaceholderVariables extends ApieException
{
    public function __construct(array $missingPlaceholders)
    {
        parent::__construct(
            500,
            sprintf(
                'A server with placeholders in the url does not have all variables defined. We miss: %s',
                implode(', ', $missingPlaceholders)
            )
        );
    }
}
