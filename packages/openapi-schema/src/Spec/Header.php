<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\ValueObjects\ValueObjectInterface;

class Header implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;
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
     * @var mixed|null
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
