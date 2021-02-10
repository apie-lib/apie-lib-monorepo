<?php


namespace Apie\OpenapiSchema\Map;

use Apie\CompositeValueObjects\ValueObjectListInterface;
use Apie\CompositeValueObjects\ValueObjectListTrait;
use Apie\OpenapiSchema\Exceptions\DuplicateParameterInParameterList;
use Apie\OpenapiSchema\Spec\Parameter;
use Apie\OpenapiSchema\Spec\Reference;
use Apie\TypeJuggling\AnotherValueObject;
use Apie\TypeJuggling\Compound;
use Apie\TypeJuggling\TypeUtilInterface;

class ParameterList implements ValueObjectListInterface
{
    use ValueObjectListTrait;

    protected static function getWantedType(string $key): TypeUtilInterface
    {
        return new Compound(
            $key,
            new AnotherValueObject($key, Parameter::class),
            new AnotherValueObject($key, Reference::class)
        );
    }

    protected function sanitizeValue(): void
    {
        $seen = [];
        foreach ($this->list as $item) {
            if ($item instanceof Parameter) {
                $name = $item->getName();
                $in = $item->getIn()->toNative();
                if (!empty($seen[$name][$in])) {
                    throw new DuplicateParameterInParameterList($name, Parameter::fromNative($in));
                }
                $seen[$name][$in] = true;
            }
        }
    }
}
