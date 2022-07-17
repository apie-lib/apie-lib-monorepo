<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemList;

class ImmutableStringOrIntList extends ItemList
{
    protected bool $mutable = false;

    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
