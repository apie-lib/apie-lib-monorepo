<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\HeaderMap;
use Apie\OpenapiSchema\Map\LinkObjectMap;
use Apie\OpenapiSchema\Map\MediaTypeMap;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Response implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $description;

    /**
     * @var HeaderMap|null
     */
    private $headers;

    /**
     * @var MediaTypeMap|null
     */
    private $content;

    /**
     * @var LinkObjectMap|null
     */
    private $links;

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}