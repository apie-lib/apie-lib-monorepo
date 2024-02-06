<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Common\ContextConstants;
use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\Fields\ConstructorParameter;
use Apie\Core\Metadata\Fields\FieldInterface;
use Apie\Core\PropertyToFieldMetadataUtil;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;
use ReflectionMethod;

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
        if (!$apieContext->hasContext('property')) {
            return false;
        }
        $property = Utils::toString($apieContext->getContext('property'));
        if ($apieContext->hasContext(ContextConstants::RESOURCE_NAME)) {
            $resourceName = $apieContext->getContext(ContextConstants::RESOURCE_NAME);
            if (!class_exists($resourceName) || $property === 'id') {
                return false;
            }
            $refl = new ReflectionClass($resourceName);
            $fieldMetadata = PropertyToFieldMetadataUtil::fromPropertyStringToFieldMetadata(
                $refl,
                $apieContext,
                $property
            );
            return $fieldMetadata instanceof FieldInterface && $this->supportsField($fieldMetadata, $apieContext);
        }
        if (false && -$apieContext->hasContext(ContextConstants::SERVICE_CLASS) && $apieContext->hasContext(ContextConstants::METHOD_NAME)) {
            $refl = new ReflectionMethod(
                $apieContext->getContext(ContextConstants::SERVICE_CLASS),
                $apieContext->getContext(ContextConstants::METHOD_NAME)
            );
            foreach ($refl->getParameters() as $parameter) {
                if ($parameter->getName() === $property) {
                    $fieldMetadata = new ConstructorParameter($parameter);
                    return $this->supportsField($fieldMetadata, $apieContext);
                }
            }
        }
        return false;
    }

    final public function getList(ApieContext $apieContext, string $searchTerm): DropdownOptionList
    {
        $property = Utils::toString($apieContext->getContext('property'));
        if ($apieContext->hasContext(ContextConstants::RESOURCE_NAME)) {
            $resourceName = $apieContext->getContext(ContextConstants::RESOURCE_NAME);
            $refl = new ReflectionClass($resourceName);
            $fieldMetadata = PropertyToFieldMetadataUtil::fromPropertyStringToFieldMetadata(
                $refl,
                $apieContext,
                $property
            );
        } else {
            assert($apieContext->hasContext());
            $refl = new ReflectionMethod(
                $apieContext->getContext(ContextConstants::SERVICE_CLASS),
                $apieContext->getContext(ContextConstants::METHOD_NAME)
            );
            foreach ($refl->getParameters() as $parameter) {
                if ($parameter->getName() === $property) {
                    $fieldMetadata = new ConstructorParameter($parameter);
                    break;
                }
            }
        }

        return $this->createDropdownList($property, $fieldMetadata, $searchTerm, $apieContext);
    }
}
