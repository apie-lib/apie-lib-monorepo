<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

class AuditRead implements AuditEvent
{
    public function __construct(
        private bool $fromList = false
    ) {
    }

    public function getEvent(): AuditLogEvent
    {
        return AuditLogEvent::Read;
    }
}
