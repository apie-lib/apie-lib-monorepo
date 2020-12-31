<?php


namespace Apie\OpenapiSchema\Concerns;


use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;

trait CompositeValueObjectWithExtension
{
    use CompositeValueObjectTrait {
        toNative as private internalToArray;
        fromNative as private internalFromArray;
    }

    /**
     * @var SpecificationExtension|null
     */
    private $specificationExtension;

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
        unset($input['specificationExtension']);
        $list = [];
        foreach ($input as $key => $value) {
            if (stripos($key, 'x-') === 0) {
                $list['specificationExtension'][$key] = $value;
            } else {
                $list[$key] = $value;
            }
        }
        return self::internalFromArray($list);
    }
}