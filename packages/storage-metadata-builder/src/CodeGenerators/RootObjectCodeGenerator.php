<?php
namespace Apie\StorageMetadataBuilder\CodeGenerators;

use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\Fields\GetterMethod;
use Apie\Core\Metadata\Fields\PublicProperty;
use Apie\Core\Metadata\MetadataFactory;
use Apie\StorageMetadata\Attributes\GetSearchIndexAttribute;
use Apie\StorageMetadataBuilder\Factories\ClassTypeFactory;
use Apie\StorageMetadataBuilder\Interfaces\RootObjectInterface;
use Apie\StorageMetadataBuilder\Interfaces\RunGeneratedCodeContextInterface;
use Apie\StorageMetadataBuilder\Mediators\GeneratedCodeContext;

/**
 * Creates the root entities.
 * - Has class RootObjectInterface
 * - Has columns starting with search_ for search on columns.
 */
final class RootObjectCodeGenerator implements RunGeneratedCodeContextInterface
{
    public function run(GeneratedCodeContext $generatedCodeContext): void
    {
        $currentObject = $generatedCodeContext->getCurrentObject();
        if (null === $currentObject || null !== $generatedCodeContext->getCurrentProperty()) {
            return;
        }
        $tableName = $generatedCodeContext->getPrefix('apie_resource_');
        $table = ClassTypeFactory::createStorageTable($tableName, $currentObject);
        $metadata = MetadataFactory::getResultMetadata($currentObject, new ApieContext());
        foreach ($metadata->getHashmap() as $fieldName => $fieldDefinition) {
            if ($fieldDefinition instanceof GetterMethod) {
                $searchProperty = $table->addProperty('search_' . $fieldName);
                $searchProperty->setType('array');
                $searchProperty->addAttribute(GetSearchIndexAttribute::class, [$fieldDefinition->getReflectionMethod()->name]);
            } elseif ($fieldDefinition instanceof PublicProperty) {
                $searchProperty = $table->addProperty('search_' . $fieldName);
                $searchProperty->setType('array');
                $searchProperty->addAttribute(GetSearchIndexAttribute::class, [$fieldName]);
            }
        }

        $table->addImplement(RootObjectInterface::class);
        $generatedCodeContext->iterateOverTable($table);
    }
}