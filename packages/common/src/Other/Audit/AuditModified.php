<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

class AuditModified implements AuditEvent
{
    public function __construct(
        private mixed $contents
    ) {
    }
    public function getEvent(): AuditLogEvent
    {
        return AuditLogEvent::Modified;
    }
}
