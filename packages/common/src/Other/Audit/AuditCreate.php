<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

class AuditCreate implements AuditEvent
{
    public function __construct(
        private bool $withId
    ) {
    }

    public function getEvent(): AuditLogEvent
    {
        return $this->withId ? AuditLogEvent::Replaced : AuditLogEvent::Created;
    }
}
