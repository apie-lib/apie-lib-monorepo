<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RuntimeCheck;
use App\ApiePlayground\Permission\Identifiers\CompanyIdentifier;

class Company implements \Apie\Core\Entities\EntityInterface
{
    private CompanyIdentifier $id;

    #[RuntimeCheck(new LoggedIn())]
    public function __construct()
    {
        $this->id = CompanyIdentifier::createRandom();
    }

    public function getId(): CompanyIdentifier
    {
        return $this->id;
    }
}
