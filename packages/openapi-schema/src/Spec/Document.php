<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\InfoContract;
use Apie\OpenapiSchema\Map\SecurityRequirementList;
use Apie\OpenapiSchema\Map\ServerList;
use Apie\OpenapiSchema\Map\TagList;
use Apie\OpenapiSchema\ValueObjects\OpenApiVersion;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

final class Document implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var OpenApiVersion
     */
    private $openapi;

    /**
     * @var InfoContract|Info
     */
    private $info;

    /**
     * @var ServerList|null
     */
    private $servers;

    /**
     * @var Paths
     */
    private $paths;

    /**
     * @var Components|null
     */
    private $components;

    /**
     * @var SecurityRequirementList|null
     */
    private $security;

    /**
     * @var TagList|null
     */
    private $tags;

    /**
     * @var ExternalDocs|null
     */
    private $externalDocs;

    public function __construct(InfoContract $info, Paths $paths)
    {
        $this->openapi = new OpenApiVersion('');
        $this->info = $info;
        $this->paths = $paths;
    }
}