<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Map\EncodingMap;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class MediaType implements ValueObjectCompareInterface, ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var SchemaContract|Schema|null
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
     * @var EncodingMap|null
     */
    private $encoding;
}