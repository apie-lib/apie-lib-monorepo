<?php


namespace Apie\StaticResourcesPlugin;

use Apie\Core\PluginInterfaces\ResourceProviderInterface;

class StaticResourcesPlugin implements ResourceProviderInterface
{
    private $resources;

    /**
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }
    /**
     * Returns a list of Api resources.
     *
     * @return string[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }
}
