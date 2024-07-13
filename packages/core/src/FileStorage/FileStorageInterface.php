<?php
namespace Apie\Core\FileStorage;

use Psr\Http\Message\UploadedFileInterface;

interface FileStorageInterface
{
    /**
     * @template T of StoredFile
     * @param class-string<T> $className
     * @return T
     */
    public function createNewUpload(
        UploadedFileInterface $fileUpload,
        string $className = StoredFile::class
    ): StoredFile;
}