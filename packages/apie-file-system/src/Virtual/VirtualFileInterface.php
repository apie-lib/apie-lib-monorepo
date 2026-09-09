<?php
namespace Apie\ApieFileSystem\Virtual;

interface VirtualFileInterface
{
    public function getName(): string;
    /**
     * @return string|resource
     */
    public function getContents(): mixed;
    public function getSize(): ?int;
    public function getMimeType(): string;
}
