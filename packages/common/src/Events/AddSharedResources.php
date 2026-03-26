<?php
namespace Apie\Common\Events;

use Apie\Common\Other\AuditLog;
use Apie\Core\Attributes\Auditable;
use Apie\Core\BackgroundProcess\SequentialBackgroundProcess;
use Apie\Core\BoundedContext\BoundedContext;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddSharedResources implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [RegisterBoundedContexts::class => 'addSharedResources'];
    }

    public function addSharedResources(RegisterBoundedContexts $registerBoundedContexts): void
    {
        foreach ($registerBoundedContexts->hashmap as $boundedContext) {
            $resources = $boundedContext->resources;
            /** @var BoundedContext $boundedContext */
            $lists = $boundedContext->findRelatedClasses()->toStringArray();
            if (in_array(SequentialBackgroundProcess::class, $lists)) {
                $resources[] = new ReflectionClass(SequentialBackgroundProcess::class);
            }
            $auditLogAdded = false;
            foreach ($boundedContext->resources as $resource) {
                foreach ($resource->getAttributes(Auditable::class) as $auditable) {
                    $auditLogAdded = true;
                }
            }
            if ($auditLogAdded) {
                $resources[] = new ReflectionClass(AuditLog::class);
            }
        }
    }
}
