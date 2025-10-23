<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Common\ActionDefinitions\GetResourceListActionDefinition;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Serializer\Serializer;

class GetResourceListFolder implements VirtualFolderInterface
{
    public function __construct(
        private readonly GetResourceListActionDefinition $actionDefinition,
        private readonly ApieContext $apieContext
    ) {
    }

    public function getName(): string
    {
        return $this->actionDefinition->getResourceName()->getShortName();
    }

    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null
    {
        return null;
    }

    public function getChildren(): VirtualFileMap
    {
        $datalayer = $this->apieContext->getContext(ApieDatalayer::class, false);
        $serializer = $this->apieContext->getContext(Serializer::class, false);
        if ($datalayer instanceof ApieDatalayer && $serializer instanceof Serializer) {
            $list = $datalayer->all(
                $this->actionDefinition->getResourceName(),
                $this->apieContext->getContext(ContextConstants::BOUNDED_CONTEXT_ID, false)
            );
            $totalCount = $list->getTotalCount();
            $files = [];
            for ($i = 0; $i < $totalCount; $i+= GetResourceListPaginationFolder::ITEMS_PER_PAGE) {
                $page = (int) floor($i / GetResourceListPaginationFolder::ITEMS_PER_PAGE);
                $files[$page] = new GetResourceListPaginationFolder($list, $page, $serializer, $this->apieContext);
            }
            return new VirtualFileMap($files);
        }
        return new VirtualFileMap();
    }
}
