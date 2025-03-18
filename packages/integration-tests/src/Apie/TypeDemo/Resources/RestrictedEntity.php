<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\ContextConstants;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\PermissionList;
use Apie\Core\Permissions\RequiresPermissionsInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\RestrictedEntityIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\TextValueObjects\CompanyName;

#[RemovalCheck(new StaticCheck(new LoggedIn()))]
final class RestrictedEntity implements EntityInterface, RequiresPermissionsInterface
{
    private ?UserIdentifier $userId = null;

    public function __construct(
        private RestrictedEntityIdentifier $id,
        public CompanyName $companyName,
        #[Context(ContextConstants::AUTHENTICATED_USER)]
        ?User $user = null
    ) {
        $this->userId = $user?->getId();
    }

    public function getRequiredPermissions(): PermissionList
    {
        if ($this->userId) {
            return new PermissionList([$this->userId->toPermission()]);
        }
        return new PermissionList();
    }

    public function getId(): RestrictedEntityIdentifier
    {
        return $this->id;
    }

    public function getUserId(): ?UserIdentifier
    {
        return $this->userId;
    }
}
