<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Fixtures\Enums\ColorEnum;

class CompositeValueObjectExample implements ValueObjectInterface
{
    use CompositeValueObject;

    private string $string;

    private int $integer;

    private float $floatingPoint;

    private bool $trueOrFalse;

    private mixed $mixed;

    private ColorEnum $color;

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

    public function getColor(): ColorEnum
    {
        return $this->color;
    }
}
