<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\TextValueObjects\EncryptedPassword;
use Apie\TextValueObjects\StrongPassword;
use LogicException;

final class User implements EntityInterface
{
    private ?EncryptedPassword $password = null;

    private bool $blocked = false;

    private UuidV4 $activationToken;

    private ?DutchPhoneNumber $phoneNumber = null;

    public function __construct(
        private readonly UserIdentifier $id
    ) {
        $this->activationToken = UuidV4::createRandom();
    }

    public function getId(): UserIdentifier
    {
        return $this->id;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function block(): void
    {
        if ($this->blocked) {
            throw new LogicException("User is already blocked!");
        }
        $this->blocked = true;
    }

    public function unblock(): void
    {
        if (!$this->blocked) {
            throw new LogicException("User is not blocked!");
        }
        $this->blocked = false;
    }

    public function setPhoneNumber(?DutchPhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): DutchPhoneNumber
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
        if ($this->blocked) {
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
        return $this->password->verifyUnencryptedPassword($password);
    }
}
