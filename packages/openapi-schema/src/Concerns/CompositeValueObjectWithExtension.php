<?php


namespace Apie\OpenapiSchema\Concerns;

use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\CompositeValueObjects\Exceptions\FieldMissingException;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;

trait CompositeValueObjectWithExtension
{
    use CompositeValueObjectTrait {
        toNative as private internalToArray;
        supportsFromNative as private internalSupportsFromNative;
        fromNative as private internalFromArray;
        with as private internalWith;
    }

    /**
     * @var \Apie\OpenapiSchema\ValueObjects\SpecificationExtension|null
     */
    private $specificationExtension;

    public static function supportsFromNative($value)
    {
        if ($value instanceof ValueObjectInterface) {
            $value = $value->toNative();
        }
        if (!is_array($value)) {
            return false;
        }
        $new = [];
        foreach ($value as $key => $val) {
            if (stripos($key, 'x-') !== 0) {
                $new[$key] = $val;
            }
        }
        return static::internalSupportsFromNative($new);
    }

    public function toNative()
    {
        $result = $this->internalToArray();
        if (isset($result['specificationExtension'])) {
            $extensions = $result['specificationExtension'];
            unset($result['specificationExtension']);
            $result = array_merge($result, $extensions);
        }
        return array_filter(
            $result,
            function ($value) {
                return $value !== null;
            }
        );
    }

    public static function fromNative($input)
    {
        if ($input instanceof ValueObjectInterface) {
            $input = $input->toNative();
        }
        if (!is_array($input)) {
            throw new InvalidValueForValueObjectException($input, static::class);
        }
        unset($input['specificationExtension']);
        $list = [];
        $list['specificationExtension'] = [];
        foreach ($input as $key => $value) {
            if (stripos($key, 'x-') === 0) {
                $list['specificationExtension'][$key] = $value;
            } else {
                $list[$key] = $value;
            }
        }
        return self::internalFromArray($list);
    }

    public function with(string $fieldName, $value): self
    {
        if ($fieldName === 'specificationExtension') {
            throw new FieldMissingException($fieldName, $this);
        }
        if (stripos($fieldName, 'x-') === 0) {
            $object = clone $this;
            if ($object->specificationExtension) {
                $object->specificationExtension = $this->specificationExtension->withField($fieldName, $value);
            } else {
                $object->specificationExtension = new SpecificationExtension([$fieldName => $value]);
            }
            return $object;
        }
        return $this->internalWith($fieldName, $value);
    }
}
