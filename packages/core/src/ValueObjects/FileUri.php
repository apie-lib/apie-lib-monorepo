<?php
namespace Apie\Core\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\FileStorage\StoredFile;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

#[Description('URL to download file')]
class FileUri extends Uri implements UploadedFileInterface
{
    private StoredFile $loadedFile;

    final protected function toFile(): UploadedFileInterface
    {
        if (!isset($this->loadedFile)) {
            $contentType = null;
            if (class_exists('GuzzleHttp\Client')) {
                $client = new \GuzzleHttp\Client();
                try {
                    $response = $client->request('HEAD', $this->toNative());
                    $contentType = $response->getHeaderLine('Content-Type');
                    // Use $contentType as needed
                } catch (\Exception $e) {
                    // Fallback to existing logic
                }
            }
            $stream = fopen($this->toNative(), 'rb');
            $this->loadedFile = StoredFile::createFromResource($stream, clientMimeType: $contentType);         
        }
        
        return $this->loadedFile;
    }

    public function getStream(): StreamInterface
    {
        return $this->toFile()->getStream();
    }

    public function moveTo($targetPath): void
    {
        $this->toFile()->moveTo($targetPath);
    }

    public function getSize(): ?int
    {
        return $this->toFile()->getSize();
    }

    public function getError(): int
    {
        return $this->toFile()->getError();
    }

    public function getClientFilename(): ?string
    {
        return $this->toFile()->getClientFilename();
    }

    public function getClientMediaType(): ?string
    {
        return $this->toFile()->getClientMediaType();
    }

}