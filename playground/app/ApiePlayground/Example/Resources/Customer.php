<?php

namespace App\ApiePlayground\Example\Resources;

use Apie\Core\Attributes\FakeCount;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use App\ApiePlayground\Example\Identifiers\CustomerIdentifier;

#[FakeCount(25)]
class Customer implements \Apie\Core\Entities\EntityInterface
{
    private CustomerIdentifier $id;

    private ?DutchPhoneNumber $phoneNumber = null;

    public function __construct()
    {
        $this->id = CustomerIdentifier::createRandom();
    }

    public function getId(): CustomerIdentifier
    {
        return $this->id;
    }

    public function setPhoneNumber(DutchPhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): ?DutchPhoneNumber
    {
        return $this->phoneNumber;
    }
}
