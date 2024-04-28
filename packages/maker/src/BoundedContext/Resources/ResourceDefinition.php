<?php
namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;
use Apie\Maker\BoundedContext\Identifiers\ResourceDefinitionIdentifier;
use Apie\Maker\BoundedContext\Lists\PropertyDefinitionList;

class ResourceDefinition implements EntityInterface
{
    public function __construct(
        private ResourceDefinitionIdentifier $id,
        private PascalCaseSlug $name,
        private BoundedContextId $boundedContextId,
        private PropertyDefinitionList $properties
    ) {
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
