<?php
namespace Apie\ApieBundle\Wrappers;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Symfony\Component\HttpFoundation\RequestStack;

final class BoundedContextSelected
{
    public function __construct(
        private RequestStack $requestStack,
        private BoundedContextHashmap $boundedContextHashmap
    ) {
    }

    public function getBoundedContextFromRequest(): ?BoundedContext
    {
        $request = $this->requestStack->getMainRequest();
        if (!$request) {
            return null;
        }
        if ($request->attributes->has(RestApiRouteDefinition::BOUNDED_CONTEXT_ID)) {
            return $this->boundedContextHashmap[$request->attributes->get(RestApiRouteDefinition::BOUNDED_CONTEXT_ID)];
        }
        if ($request->attributes->has(RestApiRouteDefinition::RESOURCE_NAME)) {
            return $this->getBoundedContextFromClassName($request->attributes->get(RestApiRouteDefinition::RESOURCE_NAME));
        }
        return null;
    }

    /**
     * @param class-string $className
     */
    public function getBoundedContextFromClassName(string $className): ?BoundedContext
    {
        foreach ($this->boundedContextHashmap as $boundedContext) {
            foreach ($boundedContext->resources as $resource) {
                if ($resource->name === $className) {
                    return $boundedContext;
                }
            }
        }
        return null;
    }
}
