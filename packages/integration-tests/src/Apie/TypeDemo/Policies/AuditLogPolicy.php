<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Policies;

use Apie\Common\Other\AuditLog;

class AuditLogPolicy
{
    public function staticReadDescription(): bool
    {
        return false;
    }

    public function canViewAny(): bool
    {
        return true;
    }
    
    public function canView(AuditLog $resource): bool
    {
        return true;
    }
}
