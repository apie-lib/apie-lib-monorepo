<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CommonValueObjects\Url;
use Apie\CompositeValueObjects\Utils\MixedTypehint;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;
use LogicException;

class Example implements ValueObjectInterface
{
    /**
     * @var string|null
     */
    private $summary;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var mixed|null
     */
    private $value;

    /**
     * @var Url|null
     */
    private $externalValue;

    /**
     * @var SpecificationExtension
     */
    private $specificationExtension;

    public function __construct()
    {
        $this->specificationExtension = new SpecificationExtension([]);
    }

    public static function fromNative($value)
    {
        if (isset($value['value']) && isset($value['externalValue'])) {
            throw new LogicException('An example can only have a value or an external value');
        }
        $res = new Example();
        if (isset($value['value'])) {
            $res->value = (new MixedTypehint('value'))->fromNative($value['value']);
        }
        if (isset($value['externalValue'])) {
            $res->externalValue = Url::fromNative($value['externalValue']);
        }
        if (isset($value['summary'])) {
            $res->summary = (string) $value['summary'];
        }
        if (isset($value['description'])) {
            $res->description = (string) $value['description'];
        }
        unset($value['value'], $value['externalValue'], $value['summary'], $value['description']);
        $res->specificationExtension = new SpecificationExtension($value);
        return $res;
    }

    public function toNative()
    {
        $res = $this->specificationExtension->toNative();
        if ($this->externalValue) {
            $res['externalValue'] = $this->externalValue->toNative();
        } else {
            $res['value'] = $this->value;
        }
        if ($this->summary !== null) {
            $res['summary'] = $this->summary;
        }
        if ($this->description !== null) {
            $res['description'] = $this->description;
        }
        return $res;
    }
}