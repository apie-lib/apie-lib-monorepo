<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

class ClassWithoutProperties
{
    public function setSetterOnly(string $setterOnly): self
    {
        return $this;
    }

    public function getGetterOnly(): string
    {
        return '';
    }
}
