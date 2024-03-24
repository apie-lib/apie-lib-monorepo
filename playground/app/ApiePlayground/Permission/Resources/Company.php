<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Core\Attributes\HasRole;
use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\TextValueObjects\CompanyName;
use App\ApiePlayground\Permission\Identifiers\CompanyIdentifier;

#[RuntimeCheck(new LoggedIn())]
class Company implements \Apie\Core\Entities\EntityInterface
{
    private CompanyIdentifier $id;

    #[RuntimeCheck(new HasRole('ADMIN', 'MANAGER'))]
    public function __construct(private CompanyName $companyName)
    {
        $this->id = CompanyIdentifier::createRandom();
    }

    #[RuntimeCheck(new HasRole('ADMIN', 'MANAGER'))]
    public function setCompanyName(CompanyName $companyName)
    {
        $this->companyName = $companyName;
    }

    public function getCompanyName(): CompanyName
    {
        return $this->companyName;
    }

    public function getId(): CompanyIdentifier
    {
        return $this->id;
    }
}
