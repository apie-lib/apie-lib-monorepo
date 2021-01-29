<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CommonValueObjects\KebabCaseString;
use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#xml-object
 */
class Xml implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    public function __construct()
    {
        $this->specificationExtension = new SpecificationExtension([]);
    }

    /**
     * @var KebabCaseString|null
     */
    private $name;

    /**
     * @var Url|null
     */
    private $namespace;

    /**
     * @var KebabCaseString|null
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

    /**
     * @return KebabCaseString|null
     */
    public function getName(): ?KebabCaseString
    {
        return $this->name;
    }

    /**
     * @return Url|null
     */
    public function getNamespace(): ?Url
    {
        return $this->namespace;
    }

    /**
     * @return KebabCaseString|null
     */
    public function getPrefix(): ?KebabCaseString
    {
        return $this->prefix;
    }

    /**
     * @return bool
     */
    public function isAttribute(): bool
    {
        return $this->attribute ?? false;
    }

    /**
     * @return bool
     */
    public function isWrapped(): bool
    {
        return $this->wrapped ?? false;
    }
}