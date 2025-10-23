<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\Core\Context\ApieContext;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifier\IdentifierInterface;
use Apie\Serializer\Serializer;

class GetSingleResourceFile implements VirtualFileInterface
{
    public function __construct(
        private readonly EntityInterface $resource,
        private readonly Serializer $serializer,
        private readonly ApieContext $context,
    ) {
    }

    public function getName(): string
    {
        /** @var IdentifierInterface<EntityInterface> */
        $identifier = $this->resource->getId();
        return $identifier->toNative() . '.json';
    }

    public function getContents(): string
    { 
        return json_encode(
            $this->serializer->normalize($this->resource, $this->context),
            JSON_PRETTY_PRINT
        );
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
