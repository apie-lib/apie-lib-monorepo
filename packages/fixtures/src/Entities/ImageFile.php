<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\ImageFileIdentifier;
use Psr\Http\Message\UploadedFileInterface;

#[RemovalCheck(new StaticCheck())]
final class ImageFile implements EntityInterface
{
    public function __construct(
        private ImageFileIdentifier $id,
        private UploadedFileInterface $file,
        public ?string $alternativeText
    ) {
    }

    public function getId(): ImageFileIdentifier
    {
        return $this->id;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }
}
