<?php


namespace Apie\OpenapiSchema\ValueObjects;

use Apie\ValueObjects\ValueObjectInterface;

class SpecificationExtension implements ValueObjectInterface
{
    private $extraFields;

    public function __construct(array $extraFields)
    {
        $res = [];
        foreach ($extraFields as $fieldName => $extraField) {
            if (stripos($fieldName, 'x-') === 0) {
                $res[strtolower($fieldName)] = $extraField;
            } else {
                $res['x-' . strtolower($fieldName)] = $extraField;
            }
        }
        $this->extraFields = $res;
    }

    public function toNative(): array
    {
        return $this->extraFields;
    }

    public function withField(string $fieldName, $fieldValue): self
    {
        $array = $this->extraFields;
        if (stripos($fieldName, 'x-') === 0) {
            $array[strtolower($fieldName)] = $fieldValue;
        } else {
            $array['x-' . strtolower($fieldName)] = $fieldValue;
        }
        return new self($array);
    }

    public static function fromNative($value)
    {
        return new self($value);
    }
}
