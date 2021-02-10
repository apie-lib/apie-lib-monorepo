<?php
namespace Apie\ValueObjects;

interface ValueObjectInterface
{
    /**
     * Converts a native value into a value object.
     *
     * @param mixed $value
     * @return self
     */
    public static function fromNative($value);

    /**
     * Converts value object into a native value.
     *
     * @return mixed
     */
    public function toNative();
}
