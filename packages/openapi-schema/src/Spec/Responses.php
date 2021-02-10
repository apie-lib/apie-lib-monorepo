<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\TypeJuggling\PrimitiveArray;
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
                $result->default = self::responseFromNative($itemValue);
            } elseif (preg_match('/^\d+$/', $key)) {
                $result->statusCodeResponses[$key] = self::responseFromNative($itemValue, $key);
            } else {
                $extension[$key] = $itemValue;
            }
        }
        $result->specificationExtension = new SpecificationExtension($extension);
        return $result;
    }

    private static function responseFromNative($itemValue, string $key = 'default')
    {
        $itemValue = (new PrimitiveArray($key))->toNative($itemValue);
        if (isset($itemValue['$ref'])) {
            return Reference::fromNative($itemValue);
        }
        return Response::fromNative($itemValue);
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
