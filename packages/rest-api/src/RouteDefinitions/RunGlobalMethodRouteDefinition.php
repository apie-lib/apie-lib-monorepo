<?php
namespace Apie\RestApi\RouteDefinitions;

use Apie\Common\Actions\RunAction;
use Apie\Common\ContextConstants;
use Apie\Core\Actions\ActionResponseStatus;
use Apie\Core\Actions\ActionResponseStatusList;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\Controllers\RestApiController;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\RestApi\Lists\StringList;
use ReflectionMethod;
use ReflectionType;

class RunGlobalMethodRouteDefinition extends AbstractRestApiRouteDefinition
{
    public function __construct(ReflectionMethod $method, BoundedContextId $boundedContextId)
    {
        parent::__construct($method->getDeclaringClass(), $boundedContextId, $method);
    }

    public function getMethod(): RequestMethod
    {
        return empty($this->method->getParameters()) ? RequestMethod::GET : RequestMethod::POST;
    }

    public function getUrl(): UrlRouteDefinition
    {
        $methodName = $this->method->getName();
        if ($methodName === '__invoke') {
            return new UrlRouteDefinition($this->method->getDeclaringClass()->getShortName());
        }
        return new UrlRouteDefinition($this->method->getDeclaringClass()->getShortName() . '/' . $methodName);
    }

    public function getAction(): string
    {
        return RunAction::class;
    }

    public function getOperationId(): string
    {
        $methodName = $this->method->getName();
        $suffix = $methodName === '__invoke' ? '' : ('-' . $methodName);
        return 'call-method-' . $this->method->getDeclaringClass()->getShortName() . $suffix;
    }
}
