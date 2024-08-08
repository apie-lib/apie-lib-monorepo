<?php
namespace Apie\Maker\BoundedContext\Lists;

use Apie\Core\Lists\ItemSet;
use Apie\Maker\BoundedContext\Entities\PropertyDefinition;

final class PropertyDefinitionList extends ItemSet
{
    public function offsetGet(mixed $offset): PropertyDefinition
    {
        return parent::offsetGet($offset);
    }
}
