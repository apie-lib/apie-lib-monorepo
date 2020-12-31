<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\MediaTypeMap;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class RequestBody implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var MediaTypeMap
     */
    private $content;

    /**
     * @var bool|null
     */
    private $required = false;

    public function __construct(MediaTypeMap $content)
    {
        $this->content = $content;
    }
}