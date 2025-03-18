<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemSet;

class StringOrIntSet extends ItemSet
{
    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
