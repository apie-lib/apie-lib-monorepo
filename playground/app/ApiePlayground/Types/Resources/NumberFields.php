<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\CommonValueObjects\StarRating;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\IntegerHashmap;
use Apie\Core\Lists\IntegerList;
use App\ApiePlayground\Types\Identifiers\NumberFieldsIdentifier;

#[FakeCount(25)]
class NumberFields implements EntityInterface
{
    private NumberFieldsIdentifier $id;

    public int $integer;

    public float $floatingPoint;

    public ?int $nullableInteger;

    public ?float $nullableFloatingPoint;

    public StarRating $starRating;

    public ?StarRating $nullableStarRating;

    public IntegerList $integerList;

    public IntegerHashmap $integerHashmap;

    public ?IntegerList $nullableIntegerList;

    public ?IntegerHashmap $nullableIntegerHashmap;

    public function __construct()
    {
        $this->id = NumberFieldsIdentifier::createRandom();
    }

    public function getId(): NumberFieldsIdentifier
    {
        return $this->id;
    }
}
