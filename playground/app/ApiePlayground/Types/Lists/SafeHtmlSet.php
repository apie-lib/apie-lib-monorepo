<?php
namespace App\ApiePlayground\Types\Lists;

use Apie\CommonValueObjects\SafeHtml;
use Apie\Core\Lists\ItemSet;

class SafeHtmlSet extends ItemSet
{
    public function offsetGet(mixed $offset): SafeHtml
    {
        return parent::offsetGet($offset);
    }
}