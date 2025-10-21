<?php
namespace Apie\Webdav\RouteDefinitions;

use Apie\Common\Interfaces\GlobalRouteDefinitionProviderInterface;
use Apie\Common\RouteDefinitions\ActionHashmap;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;

class WebdavRouteDefinitionProvider implements GlobalRouteDefinitionProviderInterface
{
    public function getGlobalRoutes(): ActionHashmap
    {
        $definition = new WebdavRouteDefinition();
        $definition2 = new WebdavRootRouteDefinition();
        return new ActionHashmap([
            $definition->getOperationId() => $definition,
            $definition2->getOperationId() => $definition2,
        ]);
    }

    public function getActionsForBoundedContext(BoundedContext $boundedContext, ApieContext $apieContext): ActionHashmap
    {
        return new ActionHashmap([]);
    }
}
