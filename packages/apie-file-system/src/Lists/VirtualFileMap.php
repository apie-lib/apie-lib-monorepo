<?php
namespace Apie\ApieFileSystem\Lists;

use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Apie\ApieFileSystem\Virtual\VirtualFolderInterface;
use Apie\Core\Lists\ItemHashmap;

class VirtualFileMap extends ItemHashmap
{
    public function offsetGet(mixed $offset): VirtualFileInterface|VirtualFolderInterface
    {
        return parent::offsetGet($offset);
    }
}
