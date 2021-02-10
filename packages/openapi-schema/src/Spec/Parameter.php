<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#parameter-object
 */
class Parameter implements ValueObjectInterface
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
     * @var SchemaContract|Schema|Reference|null
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ParameterIn
     */
    public function getIn(): ParameterIn
    {
        return $this->in;
    }
}
