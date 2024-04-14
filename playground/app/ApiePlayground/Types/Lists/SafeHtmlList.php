<?php
namespace App\ApiePlayground\Types\Lists;

use Apie\CommonValueObjects\SafeHtml;
use Apie\Core\Lists\ItemList;
use Apie\CommonValueObjects\Stars;

class SafeHtmlList extends ItemList
{
    public function offsetGet(mixed $offset): SafeHtml
    {
        return parent::offsetGet($offset);
    }
}