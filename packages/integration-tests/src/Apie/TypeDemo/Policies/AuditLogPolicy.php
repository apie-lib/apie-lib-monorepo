<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Policies;

use Apie\Common\Other\AuditLog;

class AuditLogPolicy
{
    public function canViewAny(): bool
    {
        return true;
    }
    
    public function canView(?AuditLog $resource = null): bool
    {
        return true;
    }
}
