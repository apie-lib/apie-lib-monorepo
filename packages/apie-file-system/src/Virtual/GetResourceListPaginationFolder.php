<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Core\Datalayers\Lists\EntityListInterface;
use Apie\Core\Datalayers\Search\QuerySearch;

class GetResourceListPaginationFolder implements VirtualFolderInterface
{
    public const ITEMS_PER_PAGE = 100;

    private ?VirtualFileMap $children = null;

    public function __construct(
        private readonly EntityListInterface $list,
        private readonly int $page
    ) {
    }

    public function getName(): string
    {
        return (string) $this->page;
    }

    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null
    {
        return $this->getChildren()[$name] ?? null;
    }

    public function getChildren(): VirtualFileMap
    {
        if ($this->children === null) {
            $this->children = new VirtualFileMap();
            $list = $this->list->toPaginatedResult(new QuerySearch($this->page, self::ITEMS_PER_PAGE));
            foreach ($list as $item) {
                $this->children[$item->getId()->toNative()] = new GetSingleResourceFile($item);
            }
        }
        return $this->children;
    }
}
