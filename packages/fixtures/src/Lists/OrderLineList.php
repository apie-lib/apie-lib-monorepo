<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemList;
use Apie\Fixtures\Entities\OrderLine;

class OrderLineList extends ItemList
{
    public function offsetGet($offset): OrderLine
    {
        return parent::offsetGet($offset);
    }
}
