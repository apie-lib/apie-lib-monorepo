<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Common\Interfaces\CheckLoginStatusInterface;
use Apie\Common\Interfaces\HasPermissionsInterface;
use Apie\Common\Interfaces\HasRolesInterface;
use Apie\Core\Lists\PermissionList;
use Apie\Core\Permissions\AllPermission;
use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\FullName;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\HasRole;
use Apie\Core\Attributes\Internal;
use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\StringList;
use Apie\Core\Permissions\PermissionInterface;
use Apie\DateValueObjects\LocalDate;
use Apie\Serializer\Exceptions\ValidationException;
use App\ApiePlayground\Permission\Enums\UserRole;
use App\ApiePlayground\Permission\Identifiers\CompanyIdentifier;
use App\ApiePlayground\Permission\Identifiers\UserIdentifier;
use LogicException;

#[FakeCount(5)]
class User implements EntityInterface, CheckLoginStatusInterface, HasRolesInterface, PermissionInterface
{
    private UserIdentifier $id;

    private ?string $blockedReason = null;

    private ?LocalDate $blockedAt = null;

    private ?UserIdentifier $blockedBy = null;

    private ?UserIdentifier $unblockedBy = null;

    private ?CompanyIdentifier $company = null;

    #[RuntimeCheck(new LoggedIn())]
    public function __construct(private readonly Email $email, private FullName $fullName, private UserRole $userRole)
    {
        $this->id = UserIdentifier::createRandom();
    }

    #[RuntimeCheck(new LoggedIn())]
    public function setCompany(?CompanyIdentifier $company)
    {
        $this->company = $company;

        return $this;
    }

    #[RuntimeCheck(new LoggedIn())]
    public function setFullName(FullName $fullName): User
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function setUserRole(UserRole $userRole)
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserRole(): UserRole
    {
        return $this->userRole;
    }

    #[RuntimeCheck(new LoggedIn())]
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    public function isDisabled(): bool
    {
        return $this->blockedAt !== null;
    }

    public function block(
        #[Context('authenticated')] User $user,
        ?string $reason
    ): void {
        if ($this->blockedAt) {
            throw ValidationException::createFromArray(['' => new LogicException('User ' . $this->id . ' is already blocked!')]);
        }
        $this->blockedBy = $user->getId();
        $this->unblockedBy = null;
        $this->blockedAt = LocalDate::createFromCurrentTime();
;       $this->blockedReason = $reason;
    }

    public function unblock(
        #[Context('authenticated')] User $user
    ): void {
        if (!$this->blockedAt) {
            throw ValidationException::createFromArray(['' => new LogicException('User ' . $this->id . ' is not blocked.')]);
        }
        $this->blockedAt = LocalDate::createFromCurrentTime();
        $this->blockedReason = null;
        $this->blockedBy = null;
        $this->unblockedBy = $user->getId();
    }

    #[RuntimeCheck(new LoggedIn(), new HasRole('ADMIN'))]
    public function getBlockedReason(): ?string
    {
        return $this->blockedReason;
    }

    public function getCompany(): ?CompanyIdentifier
    {
        return $this->company;
    }

    public function getId(): UserIdentifier
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    #[RuntimeCheck(new LoggedIn(), new HasRole('ADMIN'))]
    public function getBlockedBy(): ?UserIdentifier
    {
        return $this->blockedBy;
    }

    #[RuntimeCheck(new LoggedIn(), new HasRole('ADMIN'))]
    public function getUnblockedBy(): ?UserIdentifier
    {
        return $this->unblockedBy;
    }

    #[Internal]
    public function getRoles(): StringList
    {
        return new StringList([$this->userRole->name]);
    }

    #[Internal]
    public function getPermissionIdentifiers(): PermissionList
    {
        return new PermissionList([
            new AllPermission(new Identifier('user'))
        ]);
    }
}
