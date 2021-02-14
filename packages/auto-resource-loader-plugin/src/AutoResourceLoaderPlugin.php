<?php
namespace Apie\AutoResourceLoaderPlugin;

use Apie\Core\PluginInterfaces\ResourceProviderInterface;
use HaydenPierce\ClassFinder\ClassFinder;

/**
 * Returns all classes in a specific namespace to be used as API resources.
 */
class AutoResourceLoaderPlugin implements ResourceProviderInterface
{
    /**
     * @var string
     */
    private $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Returns all api resources.
     *
     * @return string[]
     */
    public function getResources(): array
    {
        return ClassFinder::getClassesInNamespace($this->namespace);
    }
}
