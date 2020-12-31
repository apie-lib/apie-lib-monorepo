<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Tag implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var ExternalDocs|null
     */
    private $externalDocs;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}