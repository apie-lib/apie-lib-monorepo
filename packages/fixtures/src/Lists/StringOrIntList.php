<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemList;

class StringOrIntList extends ItemList
{
    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
