<?php


namespace Apie\TypeJuggling;

use Apie\TypeJuggling\Exceptions\CanNotCallFromNativeWithAbstractClassOrInterface;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\TypeJuggling\Exceptions\OnlyValueObjectInterfaceSupportException;
use Apie\ValueObjects\ValueObjectInterface;

class AnotherValueObject implements TypeUtilInterface
{
    /**
     * @var string
     */
    private $fieldName;
    /**
     * @var string
     */
    private $className;

    public function __construct(string $fieldName, ?string $className)
    {
        if ($className === null || !is_a($className, ValueObjectInterface::class, true)) {
            throw new OnlyValueObjectInterfaceSupportException($fieldName, $className);
        }
        $this->fieldName = $fieldName;
        $this->className = $className;
    }

    public function fromNative($input)
    {
        if (is_callable([$this->className, 'fromNative'])) {
            return $this->className::fromNative($input);
        }
        throw new CanNotCallFromNativeWithAbstractClassOrInterface($this->className);
    }

    public function toNative($input)
    {
        return $input instanceof ValueObjectInterface ? $input->toNative() : null;
    }

    public function fromMissingValue()
    {
        throw new MissingValueException($this->fieldName);
    }

    public function supports($input): bool
    {
        return is_a($input, $this->className);
    }

    public function supportsToNative($input): bool
    {
        return $this->supports($input);
    }

    public function supportsFromNative($input): bool
    {
        $res = is_callable([$this->className, 'fromNative']);
        if (!$res) {
            return false;
        }
        if (is_callable([$this->className, 'supportsFromNative'])) {
            return (bool) $this->className::supportsFromNative($input);
        }
        return true;
    }

    public function __toString()
    {
        return $this->className;
    }
}
