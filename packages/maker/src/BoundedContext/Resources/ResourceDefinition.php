<?php
namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Maker\BoundedContext\Entities\PropertyDefinition;
use Apie\Maker\BoundedContext\Identifiers\BoundedContextDefinitionIdentifier;
use Apie\Maker\BoundedContext\Identifiers\ResourceDefinitionIdentifier;
use Apie\Maker\BoundedContext\Lists\PropertyDefinitionList;
use Apie\Maker\Enums\IdType;

class ResourceDefinition implements EntityInterface
{
    public function __construct(
        private ResourceDefinitionIdentifier $id,
        public IdType $idType,
        private PascalCaseSlug $name,
        public BoundedContextDefinitionIdentifier $boundedContextId,
        private PropertyDefinitionList $properties
    ) {
        foreach ($properties as $property) {
            if (strtolower($property->name->toNative()) === 'id') {
                unset($properties[$property]);
                break;
            }
        }
    }

    public function getName(): PascalCaseSlug
    {
        return $this->name;
    }

    public function addPropertyDefinition(PropertyDefinition $propertyDefinition): self
    {
        $this->properties->append($propertyDefinition);
        return $this;
    }

    public function getProperties(): PropertyDefinitionList
    {
        return $this->properties;
    }

    public function getId(): ResourceDefinitionIdentifier
    {
        return $this->id;
    }
}
