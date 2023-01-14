<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Common\ContextConstants;
use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\Fields\FieldInterface;
use Apie\Core\Metadata\MetadataFactory;
use Apie\Core\PropertyToFieldMetadataUtil;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;

abstract class BaseDropdownOptionProvider implements DropdownOptionProviderInterface
{
    abstract protected function supportsField(FieldInterface $fieldMetadata, ApieContext $apieContext): bool;

    abstract protected function createDropdownList(
        string $property,
        FieldInterface $fieldMetadata,
        string $searchTerm,
        ApieContext $apieContext
    ): DropdownOptionList;

    final public function supports(ApieContext $apieContext): bool
    {
        if (!$apieContext->hasContext(ContextConstants::RESOURCE_NAME)
            || !$apieContext->hasContext('property')) {
            return false;
        }

        $property = Utils::toString($apieContext->getContext('property'));
        $resourceName = $apieContext->getContext(ContextConstants::RESOURCE_NAME);
        if (!class_exists($resourceName) || $property === 'id') {
            return false;
        }

        $refl = new ReflectionClass($resourceName);
        $fieldMetadata = PropertyToFieldMetadataUtil::fromPropertyStringToFieldMetadata($refl, $apieContext, $property);

        
        $metadata = $this->getMetadata($refl, $apieContext);
        $hashmap = $metadata->getHashmap();
        if (!isset($hashmap[$property])) {
            return false;
        }
        return $this->supportsField($hashmap[$property], $apieContext);
    }

    final protected function getMetadata(ReflectionClass $class, ApieContext $apieContext)
    {
        return $apieContext->hasContext('id')
            ? MetadataFactory::getModificationMetadata($class, $apieContext)
            : MetadataFactory::getCreationMetadata($class, $apieContext);
    }

    final public function getList(ApieContext $apieContext, string $searchTerm): DropdownOptionList
    {
        $resourceName = $apieContext->getContext(ContextConstants::RESOURCE_NAME);
        $refl = new ReflectionClass($resourceName);
        $property = $apieContext->getContext('property');
        $metadata = $this->getMetadata($refl, $apieContext);
        $hashmap = $metadata->getHashmap();
        $fieldMetadata = $hashmap[$property];

        return $this->createDropdownList($property, $fieldMetadata, $searchTerm, $apieContext);
    }
}
