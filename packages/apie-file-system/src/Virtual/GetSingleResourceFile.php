<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifier\IdentifierInterface;

class GetSingleResourceFile implements VirtualFileInterface
{
    public function __construct(private readonly EntityInterface $resource)
    {
    }

    public function getName(): string
    {
        /** @var IdentifierInterface<EntityInterface> */
        $identifier = $this->resource->getId();
        return $identifier->toNative();
    }

    public function getContents(): string
    { // TODO json encode
        return serialize($this->resource);
    }

    public function getSize(): int
    {
        return strlen($this->getContents());
    }

    public function getMimeType(): string
    {
        return 'application/json';
    }
}
