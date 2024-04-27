<?php
namespace Apie\Maker\BoundedContext\Lists;

use Apie\Core\Lists\ItemList;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;

final class PropertyDefinitionList extends ItemList
{
    public function offsetGet(mixed $offset): PropertyDefinition
    {
        return parent::offsetGet($offset);
    }

    public function append(mixed $propertyDefinition): self
    {
        assert($propertyDefinition instanceof PropertyDefinition);
        foreach ($this as $key => $value) {
            if (strtolower($value->name->toNative()) === strtolower($propertyDefinition->name->toNative())) {
                $this[$key] = $propertyDefinition;
                return $this;
            }
        }
        return parent::append($propertyDefinition);
    }
}
