<?php
namespace Apie\Cms\RouteDefinitions;

use Apie\Cms\Controllers\RunMethodCallOnSingleResourceFormController;
use Apie\Common\ActionDefinitions\ActionDefinitionInterface;
use Apie\Common\ActionDefinitions\RunResourceMethodDefinition;
use Apie\Common\Actions\RunItemMethodAction;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use ReflectionClass;
use ReflectionMethod;

class RunMethodCallOnSingleResourceFormRouteDefinition extends AbstractCmsRouteDefinition
{
    public static function createFrom(ActionDefinitionInterface $actionDefinition): ?AbstractCmsRouteDefinition
    {
        if ($actionDefinition instanceof RunResourceMethodDefinition) {
            return new self($actionDefinition->getResourceName(), $actionDefinition->getMethod(), $actionDefinition->getBoundedContextId());
        }
        return null;
    }

    public function __construct(ReflectionClass $class, ReflectionMethod $method, BoundedContextId $boundedContextId)
    {
        parent::__construct($class, $boundedContextId, $method);
    }

    public function getMethod(): RequestMethod
    {
        return RequestMethod::GET;
    }

    public function getUrl(): UrlRouteDefinition
    {
        $methodName = $this->method->getName();
        if ($methodName === '__invoke') {
            return new UrlRouteDefinition('/resource/action/' . $this->class->getShortName() . '/{id}');
        }
        return new UrlRouteDefinition('/resource/action/' . $this->class->getShortName() . '/{id}/' . $methodName);
    }

    public function getController(): string
    {
        return RunMethodCallOnSingleResourceFormController::class;
    }

    public function getAction(): string
    {
        return RunItemMethodAction::class;
    }

    public function getOperationId(): string
    {
        $methodName = $this->method->getName();
        $suffix = $methodName === '__invoke' ? '' : ('-' . $methodName);
        return 'form-call-resource-method-' . $this->class->getShortName() . $suffix;
    }
}