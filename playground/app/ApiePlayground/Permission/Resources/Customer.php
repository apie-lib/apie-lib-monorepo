<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Attributes\StaticCheck;
use App\ApiePlayground\Permission\Identifiers\CustomerIdentifier;
use App\ApiePlayground\Permission\Identifiers\UserIdentifier;

#[RuntimeCheck(new LoggedIn())]
#[RemovalCheck(new StaticCheck())]
#[RemovalCheck(new RuntimeCheck(new LoggedIn()))]
class Customer implements \Apie\Core\Entities\EntityInterface
{
    private CustomerIdentifier $id;
    private UserIdentifier $owner;

    public function __construct(
        #[Context('authenticated')] User $user
    ) {
        $this->id = CustomerIdentifier::createRandom();
        $this->owner = $user->getId();
    }

    public function getOwner(): UserIdentifier
    {
        return $this->owner;
    }

    public function getId(): CustomerIdentifier
    {
        return $this->id;
    }
}
