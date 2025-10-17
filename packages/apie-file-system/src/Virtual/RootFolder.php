<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\Context\ApieContext;

/**
 * Root folder of the virtual file system.
 * This folder contains all bounded contexts as subfolders.
 *
 * @see BoundedContextFolder child folders
 */
class RootFolder implements VirtualFolderInterface
{
    public function __construct(
        private readonly BoundedContextHashmap $boundedContextHashmap,
        private readonly ActionDefinitionProvider $actionDefinitionProvider,
        private readonly ApieContext $apieContext
    ) {
    }

    public function getName(): string
    {
        return 'public';
    }

    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null
    {
        $boundedContext = $this->boundedContextHashmap[$name] ?? null;
        if ($boundedContext !== null) {
            return new BoundedContextFolder($boundedContext, $this->actionDefinitionProvider, $this->apieContext);
        }
        return null;
    }

    public function getChildren(): VirtualFileMap
    {
        $folders = [];
        foreach ($this->boundedContextHashmap as $name => $boundedContext) {
            $folders[$name] = new BoundedContextFolder($boundedContext, $this->actionDefinitionProvider, $this->apieContext);
        }
        return new VirtualFileMap($folders);
    }
}
