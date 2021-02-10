<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Map\DiscriminatorMapping;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#discriminator-object
 */
class Discriminator implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var null|DiscriminatorMapping
     */
    private $mapping;

    public function __construct(string $propertyName)
    {
        $this->propertyName = $propertyName;
    }
}
