<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemSet;

class ImmutableStringOrIntSet extends ItemSet
{
    protected bool $mutable = false;

    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
