<?php
namespace Apie\Cms\RouteDefinitions;

use Apie\Cms\Controllers\DashboardController;
use Apie\Common\ContextConstants;
use Apie\Core\Actions\HasRouteDefinition;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\Core\Actions\HasActionDefinition;

class DisplayResourceOverviewRouteDefinition implements HasRouteDefinition, HasActionDefinition
{
    public function __construct(private readonly ReflectionClass $class, private readonly BoundedContextId $id)
    {
    }

    public function getMethod(): RequestMethod
    {
        return RequestMethod::GET;
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition('/'. $this->id . '/resource/' . $this->class->getShortName());
    }
    /**
     * @return class-string<object>
     */
    public function getController(): string
    {
        return GetResourceListController::class;
    }
    /**
     * @return array<string, mixed>
     */
    public function getRouteAttributes(): array
    {
        return [
            /*RestApiRouteDefinition::OPENAPI_ALL => true,*/
            ContextConstants::RESOURCE_NAME => $this->className->name,
            ContextConstants::BOUNDED_CONTEXT_ID => $this->boundedContextId->toNative(),
            ContextConstants::OPERATION_ID => $this->getOperationId(),
            ContextConstants::APIE_ACTION => $this->getAction(),
        ];
    }
    public function getOperationId(): string
    {
        return 'apie.cms.dashboard.' . $this->id;
    }
}
