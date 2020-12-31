<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Parameter implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ParameterIn
     */
    private $in;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var bool|null
     */
    private $required;

    /**
     * @var bool|null
     */
    private $deprecated;

    /**
     * @var bool|null
     */
    private $allowEmptyValue;

    /**
     * @var string|null
     */
    private $style;

    /**
     * @var bool|null
     */
    private $explode;

    /**
     * @var bool|null
     */
    private $allowReserved;

    /**
     * @var Schema|null
     */
    private $schema;

    /**
     * @var mixed
     */
    private $example;

    /**
     * @var ExampleMap|null
     */
    private $examples;

    /**
     * @var string|MediaType|null
     */
    private $content;

    public function __construct(string $name, ParameterIn $in)
    {
        $this->name = $name;
        $this->in = $in;
    }
}