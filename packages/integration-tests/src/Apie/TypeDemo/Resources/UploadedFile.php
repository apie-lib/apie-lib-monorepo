<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\ResourceName;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UploadedFileIdentifier;
use Psr\Http\Message\UploadedFileInterface;

#[RemovalCheck(new StaticCheck())]
#[ResourceName('File')]
#[FakeCount(1)]
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
        return $this->file->getStream()->detach();
    }
}
