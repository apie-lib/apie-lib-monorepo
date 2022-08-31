<?php
namespace Apie\RestApi\RouteDefinitions;

use Apie\Common\Actions\RunItemMethodAction;
use Apie\Common\ContextConstants;
use Apie\Core\Actions\ActionResponseStatus;
use Apie\Core\Actions\ActionResponseStatusList;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\Controllers\RestApiController;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\RestApi\Lists\StringList;
use ReflectionClass;
use ReflectionMethod;

/**
 * Route definition for running method on single resource
 */
class RunMethodCallOnSingleResourceRouteDefinition extends AbstractRestApiRouteDefinition
{
    /**
     * @param ReflectionClass<EntityInterface> $className
     */
    public function __construct(ReflectionClass $className, ReflectionMethod $method, BoundedContextId $boundedContextId)
    {
        parent::__construct($className, $boundedContextId, $method);
    }

    public function getOperationId(): string
    {
        return 'get-single-' . $this->className->getShortName() . '-run-' . $this->method->name;
    }
    
    public function getMethod(): RequestMethod
    {
        if ($this->method->getNumberOfParameters() === 0) {
            return RequestMethod::GET;
        }
        if (str_starts_with($this->method->name, 'remove')) {
            return RequestMethod::DELETE;
        }
        return RequestMethod::POST;
    }

    public function getUrl(): UrlRouteDefinition
    {
        $url = $this->className->getShortName();
        $url .= ($this->method->isStatic()) ? '/' : ('/{' . ContextConstants::RESOURCE_ID . '}/');
        $url .= RunItemMethodAction::getDisplayNameForMethod($this->method);
        return new UrlRouteDefinition($url);
    }

    public function getAction(): string
    {
        return RunItemMethodAction::class;
    }
}
