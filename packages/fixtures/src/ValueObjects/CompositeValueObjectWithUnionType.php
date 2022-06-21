<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

class CompositeValueObjectWithUnionType implements ValueObjectInterface
{
    use CompositeValueObject;

    private string|int $stringFirst;

    private int|string $intFirst;

    public function getStringFirst(): string|int
    {
        return $this->stringFirst;
    }

    public function getIntFirst(): int|string
    {
        return $this->intFirst;
    }
}
