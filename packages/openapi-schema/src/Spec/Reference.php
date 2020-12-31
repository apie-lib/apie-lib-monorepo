<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class Reference implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $ref;

    public function __construct(string $ref)
    {
        $this->ref = $ref;
    }

    public static function fromNative($value)
    {
        return new Reference($value['$ref']);
    }

    public function toNative()
    {
        return [
            '$ref' => $this->ref
        ];
    }
}