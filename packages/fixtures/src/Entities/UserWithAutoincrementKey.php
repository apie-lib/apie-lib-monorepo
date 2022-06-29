<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\UserAutoincrementIdentifier;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use Apie\Fixtures\ValueObjects\Password;

class UserWithAutoincrementKey implements EntityInterface
{
    private UserAutoincrementIdentifier $id;

    private ?Password $password = null;

    public function __construct(private AddressWithZipcodeCheck $address)
    {
        $this->id = UserAutoincrementIdentifier::fromNative(null);
    }

    public function getId(): UserAutoincrementIdentifier
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
