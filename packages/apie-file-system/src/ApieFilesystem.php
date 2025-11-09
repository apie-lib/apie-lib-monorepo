<?php
namespace Apie\ApieFileSystem;

use Apie\ApieFileSystem\Virtual\RootFolder;
use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Apie\ApieFileSystem\Virtual\VirtualFolderInterface;

class ApieFilesystem
{
    public function __construct(public readonly RootFolder $rootFolder)
    {
    }

    public function visit(string $path): VirtualFolderInterface|VirtualFileInterface|null
    {
        $path = ltrim('/');
        $current = $this->rootFolder;
        $visited = [$current];
        $paths = explode('/', $path);
        while (!empty($paths)) {
            $segment = array_shift($paths);
            if ($segment === '.' || $segment === '') {
                continue;
            }
            if ($segment === '..') {
                $current = array_pop($visited);
                if (empty($visited)) {
                    throw new \LogicException('Invalid path: ' . $path);
                }
                continue;
            }
            $current = $current->getChild($segment);
            if ($current === null) {
                return null;
            }
            $visited[] = $current;
        }
        return $current;
    }
}
