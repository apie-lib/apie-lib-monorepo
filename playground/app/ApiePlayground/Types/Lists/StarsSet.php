<?php
namespace App\ApiePlayground\Types\Lists;

use Apie\CommonValueObjects\Stars;
use Apie\Core\Lists\ItemSet;

class StarsSet extends ItemSet
{
    public function offsetGet(mixed $offset): Stars
    {
        return parent::offsetGet($offset);
    }
}