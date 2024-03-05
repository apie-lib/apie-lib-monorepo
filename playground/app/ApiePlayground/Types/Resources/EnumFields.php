<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\CommonValueObjects\Gender;
use Apie\CommonValueObjects\Stars;
use Apie\Core\Entities\EntityInterface;
use App\ApiePlayground\Types\Identifiers\EnumFieldsIdentifier;

class EnumFields implements EntityInterface
{
    private EnumFieldsIdentifier $id;

    public Gender $gender;

    public ?Gender $nullableGender;

    public Stars $stars;

    public ?Stars $nullableStars;

    public function __construct()
    {
        $this->id = EnumFieldsIdentifier::createRandom();
    }

    public function getId(): EnumFieldsIdentifier
    {
        return $this->id;
    }
}
