<?php
namespace Apie\RestApi\RouteDefinitions;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\Actions\GetListAction;
use Apie\RestApi\Controllers\RestApiController;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\RestApi\Lists\StringList;
use ReflectionClass;

class GetResourceListRouteDefinition implements RestApiRouteDefinition
{
    /**
     * @param ReflectionClass<EntityInterface> $className
     */
    public function __construct(private ReflectionClass $className, private BoundedContextId $boundedContextId)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        return [
            'className' => $this->className->name,
            'boundedContextId' => $this->boundedContextId->toNative(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $this->className = new ReflectionClass($data['className']);
        $this->boundedContextId = new BoundedContextId($data['boundedContextId']);
    }

    /**
     * @return ReflectionClass<EntityInterface>
     */
    public function getInputType(): ReflectionClass
    {
        return $this->className;
    }

    /**
     * @return ListOf
     */
    public function getOutputType(): ListOf
    {
        return new ListOf($this->className);
    }

    public function getDescription(): string
    {
        return 'Gets a list of resource that are an instance of ' . $this->className->getShortName();
    }

    public function getOperationId(): string
    {
        return 'get-all-' . $this->className->getShortName();
    }
    
    public function getTags(): StringList
    {
        return new StringList([$this->className->getShortName(), 'all']);
    }

    public function getMethod(): RequestMethod
    {
        return RequestMethod::GET;
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition($this->className->getShortName());
    }

    public function getController(): string
    {
        return RestApiController::class;
    }

    public function getAction(): string
    {
        return GetListAction::class;
    }

    public function getRouteAttributes(): array
    {
        return
        [
            RestApiRouteDefinition::OPENAPI_ALL => true,
            RestApiRouteDefinition::RESOURCE_NAME => $this->className->name,
            RestApiRouteDefinition::BOUNDED_CONTEXT_ID => $this->boundedContextId->toNative(),
            RestApiRouteDefinition::OPERATION_ID => $this->getOperationId(),
        ];
    }
}
