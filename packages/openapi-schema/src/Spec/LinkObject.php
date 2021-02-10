<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ParameterList;
use Apie\ValueObjects\ValueObjectInterface;

class LinkObject implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $operationRef;

    /**
     * @var string|null
     */
    private $operationId;

    /**
     * @var ParameterList|null
     */
    private $parameters;

    /**
     * @var string|null
     */
    private $requestBody;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var Server|null
     */
    private $server;
}
