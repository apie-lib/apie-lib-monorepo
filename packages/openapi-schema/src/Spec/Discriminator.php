<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Map\DiscriminatorMapping;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Discriminator implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var DiscriminatorMapping|null
     */
    private $mapping;

    public function __construct(string $propertyName)
    {
        $this->propertyName = $propertyName;
    }
}