<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;

class UserWithAddress implements EntityInterface
{
    private UserWithAddressIdentifier $id;

    public function __construct()
    {
        $this->id = UserWithAddressIdentifier::createRandom();
    }

    public function getId(): UserWithAddressIdentifier
    {
        return $this->id;
    }
}
