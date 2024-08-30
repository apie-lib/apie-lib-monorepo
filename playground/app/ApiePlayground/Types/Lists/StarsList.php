<?php
namespace App\ApiePlayground\Types\Lists;

use Apie\Core\Lists\ItemList;
use Apie\CommonValueObjects\Stars;

class StarsList extends ItemList
{
    public function offsetGet(mixed $offset): Stars
    {
        return parent::offsetGet($offset);
    }
}