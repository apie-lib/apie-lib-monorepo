<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

interface AuditEvent
{
    public function getEvent(): AuditLogEvent;
}
