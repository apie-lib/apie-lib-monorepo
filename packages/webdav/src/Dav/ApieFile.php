<?php
namespace Apie\Webdav\Dav;

use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Sabre\DAV\File;

class ApieFile extends File
{
    public function __construct(private readonly VirtualFileInterface $file)
    {
    }

    public function getName()
    {
        return $this->file->getName();
    }

    public function get()
    {
        return $this->file->getContents();
    }

    public function getSize()
    {
        return $this->file->getSize();
    }

    public function getETag()
    {
        $contents = $this->file->getContents();
        if (is_string($contents)) {
            return md5($contents);
        }
        return null;
    }

    public function getContentType()
    {
        return $this->file->getMimeType();
    }
}
