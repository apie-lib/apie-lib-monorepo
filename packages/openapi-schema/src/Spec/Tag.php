<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#tag-object
 */
class Tag implements ValueObjectInterface
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
        $this->specificationExtension = new SpecificationExtension([]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ExternalDocs|null
     */
    public function getExternalDocs(): ?ExternalDocs
    {
        return $this->externalDocs;
    }
}
