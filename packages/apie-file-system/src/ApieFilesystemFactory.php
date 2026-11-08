<?php
namespace Apie\ApieFileSystem;

use Apie\ApieFileSystem\Virtual\RootFolder;
use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\Context\ApieContext;

class ApieFilesystemFactory
{
    public function __construct(
        private readonly ActionDefinitionProvider $actionDefinitionProvider,
        private readonly BoundedContextHashmap $boundedContextHashmap,
    ) {
    }
    public function create(ApieContext $apieContext): ApieFilesystem
    {
        return new ApieFilesystem(
            new RootFolder(
                $this->boundedContextHashmap,
                $this->actionDefinitionProvider,
                $apieContext
            )
        );
    }
}
