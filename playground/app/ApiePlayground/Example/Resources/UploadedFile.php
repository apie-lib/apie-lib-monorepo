<?php
namespace App\ApiePlayground\Example\Resources;

use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\ResourceName;
use Apie\Core\Attributes\SearchFilterOption;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
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

    #[SearchFilterOption(enabled: false)]
    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }

    public function getStream(): mixed
    {
        return $this->file->getStream()->detach();
    }
}
