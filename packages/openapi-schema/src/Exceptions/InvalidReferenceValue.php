<?php


namespace Apie\OpenapiSchema\Exceptions;


use Apie\Core\Exceptions\ApieException;

class InvalidReferenceValue extends ApieException
{
    public function __construct(string $ref)
    {
        parent::__construct(
            500,
            sprintf(
                'A reference used in an OpenAPI spec should only reference items in #/components, "%s" is not valid',
                $ref
            )
        );
    }
}