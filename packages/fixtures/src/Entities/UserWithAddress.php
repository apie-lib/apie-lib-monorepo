<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use Apie\Fixtures\ValueObjects\Password;

class UserWithAddress implements EntityInterface
{
    private UserWithAddressIdentifier $id;

    private ?Password $password = null;

    public function __construct(private AddressWithZipcodeCheck $address, ?UserWithAddressIdentifier $id = null)
    {
        $this->id = $id ?? UserWithAddressIdentifier::createRandom();
    }

    public function getId(): UserWithAddressIdentifier
    {
        return $this->id;
    }

    public function getAddress(): AddressWithZipcodeCheck
    {
        return $this->address;
    }

    public function setPassword(Password $password)
    {
        $this->password = $password;
    }

    public function getPassword(): ?Password
    {
        return $this->password;
    }
}
