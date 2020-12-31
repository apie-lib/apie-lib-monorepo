<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\JavascriptRegularExpression;
use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\SchemaList;
use Apie\OpenapiSchema\Map\SchemaMap;
use Apie\OpenapiSchema\ValueObjects\SchemaTypes;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Schema implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    private function __construct()
    {
    }

    /**
     * @var SchemaTypes|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $title;
    /**
     * @var float|int|null
     */
    private $multipleOf;
    /**
     * @var float|int|null
     */
private $maximum;
    /**
     * @var float|int|null
     */
private $exclusiveMaximum;
    /**
     * @var float|int|null
     */
    private $minimum;
    /**
     * @var float|int|null
     */
    private $exclusiveMinimum;
    /**
     * @var int|null
     */
    private $maxLength;
    /**
     * @var int|null
     */
    private $minLength;
    /**
     * @var JavascriptRegularExpression|null
     */
    private $pattern;
    /**
     * @var int|null
     */
    private $maxItems;
    /**
     * @var int|null
     */
    private $minItems;
    /**
     * @var bool|null
     */
    private $uniqueItems;
    /**
     * @var int|null
     */
    private $maxProperties;
    /**
     * @var int|null
     */
    private $minProperties;
    /**
     * @var bool|null
     */
    private $required;
    /**
     * @var array
     */
    private $enum;

    /**
     * @var Schema|Reference|null
     */
    private $allOf;

    /**
     * @var Schema|Reference|null
     */
    private $anyOf;

    /**
     * @var Schema|Reference|null
     */
    private $oneOf;

    /**
     * @var Schema|Reference|null
     */
    private $not;

    /**
     * @var SchemaList|null
     */
    private $items;

    /**
     * @var bool|Schema|Reference|null
     */
    private $additionalProperties;

    /**
     * @var SchemaMap|null
     */
    private $properties;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $format;

    /**
     * @var mixed
     */
    private $default;

    /**
     * @var bool|null
     */
    private $nullable;
    /**
     * @var Discriminator|null
     */
    private $discriminator;

    /**
     * @var bool|null
     */
    private $readOnly;

    /**
     * @var bool|null
     */
    private $writeOnly;

    /**
     * @var Xml|null
     */
    private $xml;

    /**
     * @var ExternalDocs|null
     */
    private $externalDocs;

    /**
     * @var mixed
     */
    private $example;

    /**
     * @var bool|null
     */
    private $deprecated;
}