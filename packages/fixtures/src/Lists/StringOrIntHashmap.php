<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemHashmap;

class StringOrIntHashmap extends ItemHashmap
{
    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
