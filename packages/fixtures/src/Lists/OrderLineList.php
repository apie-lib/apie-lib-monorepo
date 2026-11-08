<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemSet;
use Apie\Fixtures\Entities\OrderLine;

class OrderLineList extends ItemSet
{
    public function offsetGet($offset): OrderLine
    {
        return parent::offsetGet($offset);
    }
}
