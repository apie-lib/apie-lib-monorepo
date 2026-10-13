<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\Lists\EntityListInterface;
use Apie\Core\Datalayers\Search\QuerySearch;
use Apie\Serializer\Serializer;

class GetResourceListPaginationFolder implements VirtualFolderInterface
{
    public const ITEMS_PER_PAGE = 100;

    private ?VirtualFileMap $children = null;

    public function __construct(
        private readonly EntityListInterface $list,
        private readonly int $page,
        private readonly Serializer $serializer,
        private readonly ApieContext $context,
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
                $this->children[$item->getId()->toNative() . '.json'] = new GetSingleResourceFile(
                    $item,
                    $this->serializer,
                    $this->context
                );
            }
        }
        return $this->children;
    }
}
