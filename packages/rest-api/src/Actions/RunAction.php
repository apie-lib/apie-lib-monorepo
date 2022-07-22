<?php
namespace Apie\RestApi\Actions;

use Apie\Core\Actions\ActionInterface;
use Apie\Core\Actions\HasRouteDefinition;
use Apie\Core\Context\ApieContext;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\Concerns\ConvertsResourceToResponse;
use Apie\Serializer\Serializer;
use ReflectionMethod;
use ReflectionType;

class RunAction implements ActionInterface, HasRouteDefinition
{
    use ConvertsResourceToResponse;

    public function __construct(private ReflectionMethod $method, private Serializer $serializer)
    {
    }

    public function getInputType(): ReflectionMethod
    {
        return $this->method;
    }

    public function getOutputType(): ReflectionType
    {
        return $this->method->getReturnType();
    }

    public function process(ApieContext $context): ApieContext
    {
        $object = $this->method->isStatic() ? null : $context->getContext($this->method->getDeclaringClass()->name);
        $rawContent = $context->getContext('raw-content');
        $resource = $this->serializer->denormalizeOnMethodCall($rawContent, $object, $this->method, $context);
        return $context->withContext('resource', $resource);
    }

    public function getValue(ApieContext $context): mixed
    {
        return $context->getContext('resource');
    }

    public function getMethod(): RequestMethod
    {
        return RequestMethod::GET;
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition($this->method->getName() . '/');
    }
}
