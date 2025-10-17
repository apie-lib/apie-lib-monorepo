<?php
namespace Apie\ApieFileSystem;

use Apie\ApieFileSystem\Virtual\RootFolder;

class ApieFilesystem
{
    public function __construct(public readonly RootFolder $rootFolder)
    {
    }
}
