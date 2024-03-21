<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Attributes\StaticCheck;
use App\ApiePlayground\Permission\Identifiers\CustomerIdentifier;

#[RemovalCheck(new StaticCheck())]
#[RemovalCheck(new RuntimeCheck(new LoggedIn()))]
class Customer implements \Apie\Core\Entities\EntityInterface
{
    private CustomerIdentifier $id;

    public function __construct()
    {
        $this->id = CustomerIdentifier::createRandom();
    }

    public function getId(): CustomerIdentifier
    {
        return $this->id;
    }
}
