<?php


namespace Apie\ValueObjects;

interface ValueObjectCompareInterface extends ValueObjectInterface
{
    public function isEqualTo(ValueObjectCompareInterface $to): int;
}
