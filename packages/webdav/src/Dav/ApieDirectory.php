<?php
namespace Apie\Webdav\Dav;

use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Apie\ApieFileSystem\Virtual\VirtualFolderInterface;
use Sabre\DAV\Collection;
use Sabre\DAV\INode;

class ApieDirectory extends Collection
{
    public function __construct(private readonly VirtualFolderInterface $folder)
    {
    }

    /**
     * @return INode[]
     */
    public function getChildren(): array
    {
        $children = [];
        foreach ($this->folder->getChildren() as $child) {
            if ($child instanceof VirtualFolderInterface) {
                $children[] = new ApieDirectory($child);
            } else {
                $children[] = new ApieFile($child);
            }
        }

        return $children;
    }

    public function getChild($name): INode
    {
        $child = $this->folder->getChild($name);
        if ($child instanceof VirtualFolderInterface) {
            return new ApieDirectory($child);
        } elseif ($child instanceof VirtualFileInterface) {
            return new ApieFile($child);
        }
        throw new \Sabre\DAV\Exception\NotFound('File not found: ' . $name);
    }

    public function childExists($name): bool
    {
        return null !== $this->folder->getChild($name);
    }

    public function getName()
    {
        return $this->folder->getName();
    }
}
