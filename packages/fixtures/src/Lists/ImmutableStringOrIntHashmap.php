<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemHashmap;

class ImmutableStringOrIntHashmap extends ItemHashmap
{
    protected bool $mutable = false;

    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
