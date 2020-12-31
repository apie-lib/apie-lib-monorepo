<?php


namespace Apie\ValueObjects;


interface ValueObjectCompareInterface
{
    public function isEqualTo(ValueObjectCompareInterface $to);
}