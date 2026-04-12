<?php
namespace Apie\Common\Other\Audit;

use Apie\Common\Enums\AuditLogEvent;

class AuditMethodCalled implements AuditEvent
{
    public function __construct(
        private string $methodName,
        private mixed $contents
    ) {
    }
    public function getEvent(): AuditLogEvent
    {
        return AuditLogEvent::MethodCalled;
    }
}
