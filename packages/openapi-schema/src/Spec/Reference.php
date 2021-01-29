<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\MissingValueException;
use Apie\CompositeValueObjects\Utils\PrimitiveArray;
use Apie\CompositeValueObjects\Utils\StringLiteral;
use Apie\OpenapiSchema\Exceptions\InvalidReferenceValue;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;

class Reference implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $ref;

    /**
     * @see https://swagger.io/specification/#components-object
     * @see https://swagger.io/specification/#reference-object
     */
    public function __construct(string $ref)
    {
        if (!preg_match(
            '@#/components/((x-[^/]+)|schemas|responses|parameters|examples|requestBodies|headers|securitySchemes|links|callbacks)/[a-zA-Z0-9\.\-_]+$@',
            $ref
        )) {
            throw new InvalidReferenceValue($ref);
        }
        $this->ref = $ref;
    }

    public static function fromNative($value)
    {
        if ($value instanceof ValueObjectInterface) {
            $value = $value->toNative();
        }
        if (!is_array($value)) {
            throw new InvalidValueForValueObjectException($value, __CLASS__);
        }

        $literal = new StringLiteral('$ref');
        if (!isset($value['$ref'])) {
            throw new MissingValueException('$ref');
        }
        return new Reference($literal->toNative($value['$ref']));
    }

    public function toNative()
    {
        return [
            '$ref' => $this->ref
        ];
    }
}