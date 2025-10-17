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
    {
        return serialize($this->resource);
    }
}
