<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\Core\ValueObjects\DatabaseText;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\Fixtures\ValueObjects\EncryptedPassword;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\TextValueObjects\StrongPassword;
use LogicException;

final class User implements EntityInterface
{
    private ?EncryptedPassword $password = null;

    private UuidV4 $activationToken;

    private ?DutchPhoneNumber $phoneNumber = null;

    private ?DatabaseText $blockedReason = null;

    public function __construct(
        private UserIdentifier $id
    ) {
        $this->activationToken = UuidV4::createRandom();
    }

    public function getId(): UserIdentifier
    {
        return $this->id;
    }

    public function isBlocked(): bool
    {
        return $this->blockedReason !== null;
    }

    public function getBlockedReason(): ?DatabaseText
    {
        return $this->blockedReason;
    }

    public function block(DatabaseText $blockedReason): void
    {
        if ($this->blockedReason !== null) {
            throw new LogicException("User is already blocked!");
        }
        $this->blockedReason = $blockedReason;
    }

    public function unblock(): void
    {
        if ($this->blockedReason === null) {
            throw new LogicException("User is not blocked!");
        }
        $this->blockedReason = null;
    }

    public function setPhoneNumber(?DutchPhoneNumber $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhoneNumber(): ?DutchPhoneNumber
    {
        return $this->phoneNumber;
    }

    public function activate(string $activationToken, StrongPassword $newPassword, StrongPassword $repeat): void
    {
        if ($newPassword->toNative() !== $repeat->toNative()) {
            throw new LogicException('Type the same password twice!');
        }
        if ($activationToken !== $this->activationToken->toNative()) {
            throw new LogicException('Activation token is incorrect');
        }
        if ($this->isBlocked()) {
            throw new LogicException('User is blocked and can not be activated');
        }
        $this->password = EncryptedPassword::fromUnencryptedPassword($newPassword);
    }

    public function verify(string $password): bool
    {
        if (null === $this->password) {
            throw new LogicException(
                'User is not activated yet'
            );
        }
        if ($this->isBlocked()) {
            throw new LogicException('User is blocked');
        }
        return $this->password->verifyUnencryptedPassword($password);
    }
}
