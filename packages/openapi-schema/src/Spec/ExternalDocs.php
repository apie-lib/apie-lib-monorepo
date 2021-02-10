<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

class ExternalDocs implements ValueObjectInterface
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
        $this->specificationExtension = new SpecificationExtension([]);
    }
}
