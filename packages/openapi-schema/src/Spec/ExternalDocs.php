<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class ExternalDocs implements ValueObjectCompareInterface, ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var Url|null
     */
    private $url;

    public function __construct(Url $url, ?string $description = null)
    {
        $this->url = $url;
        $this->description = $description;
    }
}