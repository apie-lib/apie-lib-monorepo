<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Lists;

use Apie\Core\Lists\ItemList;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\OrderLine;

final class OrderLineList extends ItemList
{
    public function offsetGet(mixed $offset): OrderLine
    {
        return parent::offsetGet($offset);
    }
}