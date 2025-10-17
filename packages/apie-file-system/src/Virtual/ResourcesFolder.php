<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Common\ActionDefinitionProvider;
use Apie\Common\ActionDefinitions\GetResourceListActionDefinition;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;

/**
 * A folder representing the resources in a bounded context in the virtual file system.
 *
 * @see BoundedContextFolder parent folder
 */
class ResourcesFolder implements VirtualFolderInterface
{
    private VirtualFileMap $children;

    public function __construct(
        private readonly BoundedContext $boundedContext,
        private readonly ActionDefinitionProvider $actionDefinitionProvider,
        private readonly ApieContext $apieContext
    ) {
        
    }

    public function getName(): string
    {
        return 'resources';
    }

    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null
    {
        return $this->getChildren()[$name] ?? null;
    }

    public function getChildren(): VirtualFileMap
    {
        if (!isset($this->children)) {
            $files = [];
            foreach ($this->actionDefinitionProvider->provideActionDefinitions($this->boundedContext, $this->apieContext, true) as $actionDefinition) {
                if ($actionDefinition instanceof GetResourceListActionDefinition) {
                    $files[$actionDefinition->getResourceName()->getShortName()] = new GetResourceListFolder($actionDefinition, $this->apieContext);
                }
            }
            $this->children = new VirtualFileMap($files);
        }
        return $this->children;
    }
}
