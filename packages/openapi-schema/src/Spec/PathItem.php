<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ParameterList;
use Apie\OpenapiSchema\Map\ServerList;
use Apie\ValueObjects\ValueObjectInterface;

class PathItem implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $summary;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var Operation|null
     */
    private $get;

    /**
     * @var Operation|null
     */
    private $put;

    /**
     * @var Operation|null
     */
    private $post;

    /**
     * @var Operation|null
     */
    private $delete;

    /**
     * @var Operation|null
     */
    private $options;

    /**
     * @var Operation|null
     */
    private $head;

    /**
     * @var Operation|null
     */
    private $patch;

    /**
     * @var Operation|null
     */
    private $trace;

    /**
     * @var ServerList|null
     */
    private $servers;

    /**
     * @var ParameterList|null
     */
    private $parameters;
}
