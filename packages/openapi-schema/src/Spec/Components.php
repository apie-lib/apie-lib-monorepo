<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Components implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    private $schemas;

    private $responses;

    private $parameters;

    private $examples;

    private $requestBodies;

    private $headers;

    private $securitySchemes;

    private $links;

    private $callbacks;
}