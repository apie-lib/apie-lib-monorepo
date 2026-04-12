<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

class AuditRemoved implements AuditEvent
{
    public function getEvent(): AuditLogEvent
    {
        return AuditLogEvent::Removed;
    }
}
