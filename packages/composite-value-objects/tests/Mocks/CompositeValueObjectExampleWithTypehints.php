<?php


namespace Apie\Tests\CompositeValueObjects\Mocks;

use Apie\CompositeValueObjects\CompositeValueObjectTrait;
use Apie\ValueObjects\ValueObjectInterface;

class CompositeValueObjectExampleWithTypehints implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    private int $integer;

    private ?int $nullableInteger;

    private float $float;

    private ?float $nullableFloat;

    private ?CompositeValueObjectExampleWithTypehints $recursive;

    private function __construct()
    {
    }

    /**
     * @return int
     */
    public function getInteger(): int
    {
        return $this->integer;
    }

    /**
     * @return int|null
     */
    public function getNullableInteger(): ?int
    {
        return $this->nullableInteger;
    }

    /**
     * @return float
     */
    public function getFloat(): float
    {
        return $this->float;
    }

    /**
     * @return float|null
     */
    public function getNullableFloat(): ?float
    {
        return $this->nullableFloat;
    }
}
