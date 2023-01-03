<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Common\ContextConstants;
use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\Fields\FieldInterface;
use Apie\Core\Metadata\MetadataFactory;
use Apie\Core\Metadata\MetadataInterface;
use ReflectionClass;

abstract class BaseDropdownOptionProvider implements DropdownOptionProviderInterface
{
    /**
     * @param ReflectionClass<object> $class
     */
    abstract protected function supportsClass(ReflectionClass $class, ApieContext $apieContext): bool;

    /**
     * @param ReflectionClass<object> $class
     */
    abstract protected function createDropdownList(
        ReflectionClass $class,
        MetadataInterface $metadata,
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

        $property = $apieContext->getContext('property');
        $resourceName = $apieContext->getContext(ContextConstants::RESOURCE_NAME);
        if (!class_exists($resourceName) || $property === 'id') {
            return false;
        }

        $refl = new ReflectionClass($resourceName);
        if (!$this->supportsClass($refl, $apieContext)) {
            return false;
        }
        $metadata = $this->getMetadata($refl, $apieContext);
        $hashmap = $metadata->getHashmap();
        return isset($hashmap[$property]);
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

        return $this->createDropdownList($refl, $metadata, $property, $fieldMetadata, $searchTerm, $apieContext);
    }
}
