<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Exceptions\ExampleValueAndExternalValueAreMutuallyExclusive;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

class Example implements ValueObjectInterface
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
     * @var mixed|null
     */
    private $value;

    /**
     * @var Url|null
     */
    private $externalValue;

    /**
     * @var SpecificationExtension
     */
    private $specificationExtension;

    public function __construct()
    {
        $this->specificationExtension = new SpecificationExtension([]);
    }

    private function validateProperties(): void
    {
        if (isset($this->value) && isset($this->externalValue)) {
            throw new ExampleValueAndExternalValueAreMutuallyExclusive();
        }
    }
}
