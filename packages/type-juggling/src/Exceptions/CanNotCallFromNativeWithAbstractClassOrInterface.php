<?php


namespace Apie\TypeJuggling\Exceptions;

use Apie\Core\Exceptions\ApieException;

class CanNotCallFromNativeWithAbstractClassOrInterface extends ApieException
{
    public function __construct(string $className)
    {
        parent::__construct(500, sprintf("The class %s is abstract or an interface, so can not be instantiated", $className));
    }
}
