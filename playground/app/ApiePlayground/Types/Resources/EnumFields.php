<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\CommonValueObjects\Gender;
use Apie\CommonValueObjects\Stars;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Entities\EntityInterface;
use App\ApiePlayground\Types\Identifiers\EnumFieldsIdentifier;
use App\ApiePlayground\Types\Lists\StarsList;
use App\ApiePlayground\Types\Lists\StarsSet;

#[FakeCount(25)]
class EnumFields implements EntityInterface
{
    private EnumFieldsIdentifier $id;

    public Gender|Stars $genderOrStars;

    public Gender|Stars|null $nullableGenderOrStars;

    public Gender $gender;

    public ?Gender $nullableGender;

    public Stars $stars;

    public ?Stars $nullableStars;

    public StarsList $starsList;

    public ?StarsList $nullableStarsList;

    public StarsSet $starsSet;

    public ?StarsSet $nullableStarsSet;

    public function __construct()
    {
        $this->id = EnumFieldsIdentifier::createRandom();
    }

    public function getId(): EnumFieldsIdentifier
    {
        return $this->id;
    }
}
