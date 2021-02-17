<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\JavascriptRegularExpression;
use Apie\CompositeValueObjects\Exceptions\FieldMissingException;
use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\CompositeValueObjects\ValueObjects\StringList;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Map\SchemaList;
use Apie\OpenapiSchema\Map\SchemaMap;
use Apie\OpenapiSchema\ValueObjects\SchemaTypes;

/**
 * @see https://swagger.io/specification/#schema-object
 */
class Schema implements SchemaContract
{
    use CompositeValueObjectWithExtension;

    private function __construct()
    {
    }

    protected function validateProperties(): void
    {
        if ($this->type) {
            foreach ($this->type->getRequiredFields() as $requiredField) {
                if ($this->$requiredField === null) {
                    throw new FieldMissingException($requiredField, $this);
                }
            }
            $ignoredKeys = [];
            foreach ($this->type->getConflictedFields() as $conflictedField) {
                if ($this->$conflictedField !== null) {
                    $ignoredKeys[] = $conflictedField;
                }
            }
            if ($ignoredKeys) {
                throw new IgnoredKeysException($ignoredKeys, $this->type->getRequiredFields());
            }
        }
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
     * @var float|int|bool|null
     */
    private $exclusiveMaximum;
    /**
     * @var float|int|null
     */
    private $minimum;
    /**
     * @var float|int|bool|null
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
     * @var StringList|null
     */
    private $required;
    /**
     * @var array|null
     */
    private $enum;

    /**
     * @var SchemaList|null
     */
    private $allOf;

    /**
     * @var SchemaList|null
     */
    private $anyOf;

    /**
     * @var SchemaList|null
     */
    private $oneOf;

    /**
     * @var SchemaList|null
     */
    private $not;

    /**
     * @var SchemaContract|Schema|Reference|null
     */
    private $items;

    /**
     * @var bool|SchemaContract|Schema|Reference|null
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
     * @var mixed|null
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
     * @var mixed|null
     */
    private $example;

    /**
     * @var bool|null
     */
    private $deprecated;

    public function withProperty(string $key, $propertySchema): SchemaContract
    {
        $properties = $this->properties ?? [];
        $properties[$key] = $propertySchema;
        return $this->with('properties', $properties);
    }
}
