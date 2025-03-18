<?php

namespace App\ApiePlayground\Permission\Resources;

use Apie\Core\ApieLib;
use Apie\Core\Attributes\AnyApplies;
use Apie\Core\Attributes\HasRole;
use Apie\Core\Attributes\Internal;
use Apie\Core\Attributes\LoggedIn;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Entities\RequiresRecalculatingInterface;
use Apie\Core\Enums\ConsoleCommand;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\PermissionList;
use Apie\Core\Permissions\AllPermission;
use Apie\Core\Permissions\RequiresPermissionsInterface;
use Apie\TextValueObjects\CompanyName;
use App\ApiePlayground\Permission\Identifiers\CompanyIdentifier;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

#[RuntimeCheck(new AnyApplies(new LoggedIn(), new Requires(ConsoleCommand::CONSOLE_COMMAND->value)))]
class Company implements EntityInterface, RequiresPermissionsInterface, RequiresRecalculatingInterface
{
    private CompanyIdentifier $id;

    private ?DateTimeImmutable $timestamp = null;

    #[RuntimeCheck(new HasRole('ADMIN', 'MANAGER'))]
    public function __construct(private CompanyName $companyName)
    {
        $this->timestamp = ApieLib::getPsrClock()->now()->add(DateInterval::createFromDateString('1 day'));
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

    public function isExpired(): bool
    {
        return $this->timestamp === null || $this->timestamp < ApieLib::getPsrClock()->now();
    }

    public function getDateToRecalculate(): ?DateTimeInterface
    {
        return $this->timestamp;
    }


    #[Internal]
    public function getRequiredPermissions(): PermissionList
    {
        if ($this->isExpired()) {
            return new PermissionList([new AllPermission(new Identifier('user'))]);
        }
        return new PermissionList(['company:' . $this->id, new AllPermission(new Identifier('user'))]);
    }
}
