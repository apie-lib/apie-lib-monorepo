<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\CallbackMap;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\OpenapiSchema\Map\HeaderMap;
use Apie\OpenapiSchema\Map\LinkObjectMap;
use Apie\OpenapiSchema\Map\ParameterMap;
use Apie\OpenapiSchema\Map\RequestBodyMap;
use Apie\OpenapiSchema\Map\ResponseMap;
use Apie\OpenapiSchema\Map\SchemaMap;
use Apie\OpenapiSchema\Map\SecuritySchemeMap;
use Apie\ValueObjects\ValueObjectInterface;

class Components implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var SchemaMap|null
     */
    private $schemas;

    /**
     * @var ResponseMap|null
     */
    private $responses;

    /**
     * @var ParameterMap|null
     */
    private $parameters;

    /**
     * @var ExampleMap|null
     */
    private $examples;

    /**
     * @var RequestBodyMap|null
     */
    private $requestBodies;

    /**
     * @var HeaderMap|null
     */
    private $headers;

    /**
     * @var SecuritySchemeMap|null
     */
    private $securitySchemes;

    /**
     * @var LinkObjectMap|null
     */
    private $links;

    /**
     * @var CallbackMap|null
     */
    private $callbacks;
}
