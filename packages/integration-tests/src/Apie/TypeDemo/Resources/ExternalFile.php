<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\FileStorage\StoredFile;
use Apie\Core\ValueObjects\FileUri;
use Apie\Core\ValueObjects\Uri;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\ExternalFileIdentifier;
use Psr\Http\Message\UploadedFileInterface;

#[RemovalCheck(new StaticCheck())]
#[FakeCount(0)]
#[AllowMultipart]
final class ExternalFile implements EntityInterface
{
    private StoredFile $file;
    private Uri $fileUri;

    public function __construct(
        private ExternalFileIdentifier $id,
        FileUri $fileUri
    ) {
        $this->file = StoredFile::createFromUploadedFile($fileUri);
        $this->fileUri = new Uri($fileUri->toNative());
    }

    public function getId(): ExternalFileIdentifier
    {
        return $this->id;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }

    public function getFileUri(): Uri
    {
        return $this->fileUri;
    }

    public function refreshFile(): void
    {
        $this->file = StoredFile::createFromUploadedFile(new FileUri($this->fileUri));
    }
}
