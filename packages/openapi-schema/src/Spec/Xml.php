<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Xml implements ValueObjectCompareInterface, ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string|null
     */
    private $prefix;

    /**
     * @var bool|null
     */
    private $attribute;

    /**
     * @var bool|null
     */
    private $wrapped;
}