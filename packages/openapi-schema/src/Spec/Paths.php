<?php

namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\InvalidKeyException;
use Apie\CompositeValueObjects\ValueObjectHashmapTrait;
use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\MixedTypehint;
use Apie\TypeJuggling\TypeUtilInterface;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;

/**
 * @see https://swagger.io/specification/#paths-object
 */
class Paths implements ValueObjectListInterface
{
    use ValueObjectHashmapTrait;

    private function __construct()
    {
    }

    protected function sanitizeValue(): void
    {
        foreach (array_keys($this->list) as $key) {
            if (substr($key, 0, 1) === '/') {
                return;
            }
        }
        throw new InvalidValueForValueObjectException($this->list, $this);
    }

    protected static function getWantedType(string $fieldName): TypeUtilInterface
    {
        if (stripos($fieldName, 'x-') === 0) {
            return new MixedTypehint($fieldName);
        }
        if (substr($fieldName, 0, 1) === '/') {
            return new Compound(
                $fieldName,
                new AnotherValueObject($fieldName, Reference::class),
                new AnotherValueObject($fieldName, PathItem::class)
            );
        }
        throw new InvalidKeyException($fieldName, new Paths());
    }
}
