<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Attributes\Internal;
use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use Apie\Fixtures\ValueObjects\Password;
use Apie\Fixtures\ValueObjects\EncryptedPassword;

class UserWithAddress implements EntityInterface
{
    private UserWithAddressIdentifier $id;

    private ?EncryptedPassword $password = null;

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
        $this->password = EncryptedPassword::fromUnencryptedPassword($password);
    }

    #[Internal()]
    public function hasPassword(): bool
    {
        return $this->password !== null;
    }

    public function verifyAuthentication(string $username, string $password): bool
    {
        return $this->id->toNative() === $username && $this->password->verifyUnencryptedPassword($password);
    }
}
