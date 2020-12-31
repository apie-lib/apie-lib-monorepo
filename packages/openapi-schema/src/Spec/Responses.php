<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

class Responses implements ValueObjectInterface
{
    /**
     * @var Response|null
     */
    private $default;

    /**
     * @var Response[]
     */
    private $statusCodeResponses = [];

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
        $result = new Responses();
        $extension = [];
        foreach ($value as $key => $itemValue) {
            if ($key === 'default') {
                $result->default = Response::fromNative($itemValue);
            } elseif (preg_match('/^\d+$/', $key)) {
                $result->statusCodeResponses = Response::fromNative($itemValue);
            } else {
                $extension[$key] = $itemValue;
            }
        }
        $result->specificationExtension = new SpecificationExtension($extension);
        return $result;
    }

    public function toNative()
    {
        $result = $this->specificationExtension->toNative();
        if ($this->default) {
            $result['default'] = $this->default->toNative();
        }
        foreach ($this->statusCodeResponses as $statusCode => $response) {
            $result[$statusCode] = $response->toNative();
        }
        return $result;
    }
}