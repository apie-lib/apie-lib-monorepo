<?php
namespace App\ApiePlayground\Example\Resources;

use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\ResourceName;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Utils\ConverterUtils;
use App\ApiePlayground\Example\Identifiers\UploadedFileIdentifier;
use Psr\Http\Message\UploadedFileInterface;

#[RemovalCheck(new StaticCheck())]
#[ResourceName('File')]
#[AllowMultipart]
final class UploadedFile implements EntityInterface
{
    public function __construct(
        private UploadedFileIdentifier $id,
        private UploadedFileInterface $file
    ) {
    }

    public function getId(): UploadedFileIdentifier
    {
        return $this->id;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }

    public function getStream(): mixed
    {
        $stream = $this->file->getStream();
        $resource = ConverterUtils::extractResourceFromStream($stream);
        if (!is_resource($resource)) {
            throw new \RuntimeException('Failed to convert the stream to a PHP resource');
        }
    
        return $resource;
    }
}
