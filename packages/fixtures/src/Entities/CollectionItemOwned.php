<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Context\IsActivatedUser;
use Apie\Fixtures\Identifiers\CollectionItemOwnedIdentifier;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;

class CollectionItemOwned implements EntityInterface
{
    private UserWithAddressIdentifier $createdBy;

    public function __construct(
        private CollectionItemOwnedIdentifier $id,
        #[Context('authenticated')]
        UserWithAddress $createdBy,
        private bool $owned
    ) {
        $this->createdBy = $createdBy->getId();
    }

    public function setOwned(
        #[Context('authenticated')]
        UserWithAddress $user,
        bool $owned
    ): void
    {
        if ($user->getId()->toNative() === $this->createdBy->toNative()) {
            $this->owned = $owned;
        }
    }

    public function isOwned(): bool
    {
        return $this->owned;
    }

    public function getId(): CollectionItemOwnedIdentifier
    {
        return $this->id;
    }

    #[RuntimeCheck(new IsActivatedUser())]
    public function getCreatedBy(): UserWithAddressIdentifier
    {
        return $this->createdBy;
    }
}