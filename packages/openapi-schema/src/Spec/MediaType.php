<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Exceptions\ExampleAndExamplesAreMutuallyExclusive;
use Apie\OpenapiSchema\Map\EncodingMap;
use Apie\OpenapiSchema\Map\ExampleMap;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#media-type-object
 */
class MediaType implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

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
     * @var EncodingMap|null
     */
    private $encoding;

    private function validateProperties()
    {
        if (isset($this->example) && isset($this->examples)) {
            throw new ExampleAndExamplesAreMutuallyExclusive();
        }
    }
}
