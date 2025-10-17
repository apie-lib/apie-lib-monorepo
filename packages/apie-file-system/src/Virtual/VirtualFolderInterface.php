<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\ApieFileSystem\Lists\VirtualFileMap;

interface VirtualFolderInterface
{
    public function getName(): string;
    public function getChild(string $name): VirtualFileInterface|VirtualFolderInterface|null;
    public function getChildren(): VirtualFileMap;
}
