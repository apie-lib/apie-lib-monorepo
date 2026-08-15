<?php
namespace Apie\Core\Metadata;

use Apie\Core\Context\ApieContext;
use Apie\Core\Context\MetadataFieldHashmap;
use Apie\Core\Enums\ScalarType;
use Apie\Core\Lists\StringList;
use Apie\Core\Lists\ValueOptionList;
use Apie\TypeConverter\ReflectionTypeFactory;
use ReflectionClass;

class UriMetadata implements NullableMetadataInterface
{
    public function getValueOptions(ApieContext $context, bool $runtimeFilter = false): ?ValueOptionList
    {
        return null;
    }
    /**
     * @param ReflectionClass<ValueObjectInterface> $class
     */
    public function __construct(private ReflectionClass $class)
    {
    }

    public function getDisplayName(): string
    {
        return $this->class->getShortName();
    }

    /**
     * @return ReflectionClass<Uri>
     */
    public function toClass(): ReflectionClass
    {
        return $this->class;
    }

    public function getNativeType(): MetadataInterface
    {
        return MetadataFactory::getCreationMetadata(ReflectionTypeFactory::createReflectionType('string'), new ApieContext());
    }

    public function getHashmap(): MetadataFieldHashmap
    {
        return $this->getNativeType()->getHashmap();
    }

    public function getRequiredFields(): StringList
    {
        return new StringList();
    }

    public function toScalarType(bool $ignoreNull = false): ScalarType
    {
        return ScalarType::STRING;
    }

    public function getArrayItemType(): ?MetadataInterface
    {
        return null;
    }

    public function allowsNull(): bool
    {
        return false;
    }
}