<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\ValueObjects\StringList;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\CallbackPathItemList;
use Apie\OpenapiSchema\Map\ParameterList;
use Apie\OpenapiSchema\Map\SecurityRequirementList;
use Apie\OpenapiSchema\Map\ServerList;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#operation-object
 */
class Operation implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var StringList|null
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
     * @var CallbackPathItemList|null
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

    /**
     * @return Responses|null
     */
    public function getResponses(): ?Responses
    {
        return $this->responses;
    }
}
