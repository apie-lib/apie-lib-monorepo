<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ServerVariableObjectList;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Server implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @TODO: does it allow placeholders properly or do we need a different value object here?
     * @var Url
     */
    private $url;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var ServerVariableObjectList|null
     */
    private $variables;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ServerVariableObjectList|null
     */
    public function getVariables(): ?ServerVariableObjectList
    {
        return $this->variables;
    }
}