<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\CallbackPathItemList;
use Apie\OpenapiSchema\Map\ParameterList;
use Apie\OpenapiSchema\Map\SecurityRequirementList;
use Apie\OpenapiSchema\Map\ServerList;
use Apie\OpenapiSchema\Map\TagList;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Operation implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var TagList|null
     */
    private $tags;

    /**
     * @var string|null
     */
    private $summary;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var ExternalDocs|null
     */
    private $externalDocs;

    /**
     * @var string|null
     */
    private $operationId;

    /**
     * @var ParameterList|null
     */
    private $parameters;

    /**
     * @var RequestBody|null
     */
    private $requestBody;

    /**
     * @var Responses|null
     */
    private $responses;

    /**
     * @var CallbackPathItemList|nulls
     */
    private $callbacks;

    /**
     * @var bool|null
     */
    private $deprecated;

    /**
     * @var SecurityRequirementList|null
     */
    private $security;

    /**
     * @var ServerList|null
     */
    private $servers;
}