<?php


namespace Apie\CompositeValueObjects\Utils;


use Apie\CompositeValueObjects\Exceptions\MissingValueException;

class PrimitiveArray implements TypeUtilInterface
{
    /**
     * @var string
     */
    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function fromNative($input)
    {
        $res = [];
        foreach ($input as $key => $value) {
            assert(TypeUtils::fromObjectToTypeUtilInterface($key, $value));
            $res[$key] = $value;
        }
        return $res;
    }

    public function toNative($input)
    {
        return (array) $input;
    }

    public function fromMissingValue()
    {
        throw new MissingValueException($this->fieldName);
    }

    public function supports($input): bool
    {
        return is_iterable($input);
    }

    public function supportsToNative($input): bool
    {
        return is_array($input);
    }

    public function supportsFromNative($input): bool
    {
        return $this->supportsToNative($input);
    }

    public function __toString()
    {
        return 'array';
    }
}