<?php
namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\Attributes\HideIdOnOverview;
use Apie\Core\Attributes\Not;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\ContextConstants;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;
use Apie\Maker\BoundedContext\Identifiers\BoundedContextDefinitionIdentifier;
use Apie\Maker\BoundedContext\Identifiers\ResourceDefinitionIdentifier;
use Apie\Maker\BoundedContext\Lists\PropertyDefinitionList;
use Apie\Maker\Enums\IdType;

#[HideIdOnOverview]
class ResourceDefinition implements EntityInterface
{
    public function __construct(
        private ResourceDefinitionIdentifier $id,
        #[RuntimeCheck(new Not(new Requires(ContextConstants::GET_ALL_OBJECTS)))]
        public IdType $idType,
        private PascalCaseSlug $name,
        public BoundedContextDefinitionIdentifier $boundedContextId,
        #[RuntimeCheck(new Not(new Requires(ContextConstants::GET_ALL_OBJECTS)))]
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
