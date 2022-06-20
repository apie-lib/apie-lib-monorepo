<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\CommonValueObjects\Enums\Gender;
use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

class CompositeValueObjectExample implements ValueObjectInterface
{
    use CompositeValueObject;

    private string $string;

    private int $integer;

    private float $floatingPoint;

    private bool $trueOrFalse;

    private mixed $mixed;

    private Gender $gender;

    public function getString(): string
    {
        return $this->string;
    }

    public function getInteger(): int
    {
        return $this->integer;
    }

    public function getFloatingPoint(): float
    {
        return $this->floatingPoint;
    }

    public function getTrueOrFalse(): bool
    {
        return $this->trueOrFalse;
    }

    public function getMixed(): mixed
    {
        return $this->mixed;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }
}
