<?php

namespace App\ApiePlayground\Types\Resources;

use App\ApiePlayground\Types\Identifiers\NumberFieldsIdentifier;

class NumberFields implements \Apie\Core\Entities\EntityInterface
{
    private NumberFieldsIdentifier $id;

    public int $integer;

    public float $floatingPoint;

    public ?int $nullableInteger;

    public ?float $nullableFloatingPoint;

    public function __construct()
    {
        $this->id = NumberFieldsIdentifier::createRandom();
    }

    public function getId(): NumberFieldsIdentifier
    {
        return $this->id;
    }
}
