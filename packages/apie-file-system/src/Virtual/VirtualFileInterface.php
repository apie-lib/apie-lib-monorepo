<?php
namespace Apie\ApieFileSystem\Virtual;

interface VirtualFileInterface
{
    public function getName(): string;
    public function getContents(): string;
    public function getSize(): int;
    public function getMimeType(): string;
}
