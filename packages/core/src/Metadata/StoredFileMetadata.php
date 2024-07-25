<?php
namespace Apie\Core\Metadata;

use Apie\Core\Context\ApieContext;
use Apie\Core\Context\MetadataFieldHashmap;
use Apie\Core\Enums\ScalarType;
use Apie\Core\Lists\StringList;
use Apie\Core\Lists\ValueOptionList;
use ReflectionClass;

final class StoredFileMetadata implements MetadataInterface
{
    /**
     * @param ReflectionClass<StoredFile> $class
     */
    public function __construct(private readonly ReflectionClass $class)
    {
    }

    public function getValueOptions(ApieContext $context, bool $runtimeFilter = false): ?ValueOptionList
    {
        return null;
    }

    public function toClass(): ?ReflectionClass
    {
        return $this->class;
    }

    public function getHashmap(): MetadataFieldHashmap
    {
        return new MetadataFieldHashmap();
    }

    public function getRequiredFields(): StringList
    {
        return new StringList([]);
    }

    public function toScalarType(): ScalarType
    {
        return ScalarType::MIXED;
    }

    public function getArrayItemType(): ?MetadataInterface
    {
        return null;
    }
}
