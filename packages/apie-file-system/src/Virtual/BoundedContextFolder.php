<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;

/**
 * A folder representing a bounded context in the virtual file system.
 *
 * @see RootFolder parent instance
 * @see ResourcesFolder child instances
 */
class BoundedContextFolder implements VirtualFolderInterface
{
    public function __construct(
        private readonly BoundedContext $boundedContext,
        private readonly ActionDefinitionProvider $actionDefinitionProvider,
        private readonly ApieContext $apieContext
    ) {
    }

    public function getName(): string
    {
        return $this->boundedContext->getId()->toNative();
    }

    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null
    {
        if ($name === 'resources') {
            return new ResourcesFolder(
                $this->boundedContext,
                $this->actionDefinitionProvider,
                $this->apieContext
            );
        }
        return null;
    }

    public function getChildren(): VirtualFileMap
    {
        return new VirtualFileMap([
            'resources' => new ResourcesFolder(
                $this->boundedContext,
                $this->actionDefinitionProvider,
                $this->apieContext
            ),
        ]);
    }
}
