<?php
namespace Apie\StorageMetadataBuilder\CodeGenerators;

use Apie\Core\Context\ApieContext;
use Apie\Core\Enums\ScalarType;
use Apie\Core\Identifiers\KebabCaseSlug;
use Apie\Core\Metadata\ItemHashmapMetadata;
use Apie\Core\Metadata\ItemListMetadata;
use Apie\Core\Metadata\MetadataFactory;
use Apie\Core\Utils\ConverterUtils;
use Apie\StorageMetadata\Attributes\OneToManyAttribute;
use Apie\StorageMetadata\Attributes\OrderAttribute;
use Apie\StorageMetadata\Attributes\ParentAttribute;
use Apie\StorageMetadataBuilder\Factories\ClassTypeFactory;
use Apie\StorageMetadataBuilder\Interfaces\RunGeneratedCodeContextInterface;
use Apie\StorageMetadataBuilder\Mediators\GeneratedCodeContext;

/**
 * Creates the one to many relations for lists.
 * - create a sub table for the list
 * - the sub table references the entity with 'parent' property
 * - an 'order' property is made for the index of the hashmap or the order of the list.
 */
final class ItemListCodeGenerator implements RunGeneratedCodeContextInterface
{
    public function run(GeneratedCodeContext $generatedCodeContext): void
    {
        $property = $generatedCodeContext->getCurrentProperty();
        $class = $property ? ConverterUtils::toReflectionClass($property) : null;
        $currentTable = $generatedCodeContext->getCurrentTable();
        if (null === $class || null === $currentTable) {
            return;
        }
        $metadata = MetadataFactory::getMetadataStrategyForType($property->getType())
            ->getResultMetadata(new ApieContext());
        $propertyName = 'apie_'
            . str_replace('-', '_', (string) KebabCaseSlug::fromClass($property->getDeclaringClass()))
            . '_'
            . str_replace('-', '_', (string) KebabCaseSlug::fromClass($property));
        if ($currentTable->hasProperty($propertyName)) {
            return;
        }
        if ($metadata instanceof ItemListMetadata || $metadata instanceof ItemHashmapMetadata) {
            $tableName = $generatedCodeContext->getPrefix('apie_resource_');
            $arrayType = $class->getMethod('offsetGet')->getReturnType();
            $scalar = MetadataFactory::getScalarForType($arrayType, $arrayType->allowsNull());
            if (!$arrayType || 'mixed' === (string) $arrayType) {
                return;
            }
            $arrayClass = $arrayType ? ConverterUtils::toReflectionClass($arrayType) : null;
            $table = in_array($scalar, ScalarType::PRIMITIVES)
                ? ClassTypeFactory::createPrimitiveTable($tableName, $scalar->toReflectionType())
                : ClassTypeFactory::createStorageTable($tableName, $arrayClass);
            $table->addProperty('parent')
                ->setType($currentTable->getName())
                ->addAttribute(ParentAttribute::class);
            $table->addProperty('listOrder')
                ->setType($metadata instanceof ItemListMetadata ? 'int' : 'string')
                ->addAttribute(OrderAttribute::class);
            if (in_array($scalar, ScalarType::PRIMITIVES)) {
                $generatedCodeContext->generatedCode->generatedCodeHashmap[$tableName] = $table;
            } else {
                $generatedCodeContext->withCurrentObject($arrayClass)->iterateOverTable($table);
            }
            $currentTable->addProperty($propertyName)
                ->addAttribute(OneToManyAttribute::class, [$property->name, $tableName, $property->getDeclaringClass()->name]);
        }
    }
}
