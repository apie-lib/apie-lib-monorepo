<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

class Callback implements ValueObjectInterface
{
    /**
     * @var PathItem[]
     */
    private $paths = [];
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
        $res = new Callback();
        foreach ($value as $key => $itemValue) {
            if (stripos($key, 'x-') === false) {
                $res->paths[$key] = PathItem::fromNative($itemValue);
            } else {
                $res->specificationExtension = $res->specificationExtension->withField($key, $value);
            }
        }
        return $res;
    }

    public function toNative()
    {
        // TODO: Implement toNative() method.
    }
}