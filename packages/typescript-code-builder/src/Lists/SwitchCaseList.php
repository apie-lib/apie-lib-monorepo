<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\SwitchCase;

class SwitchCaseList extends ItemList
{
    public function offsetGet(mixed $offset): SwitchCase
    {
        return parent::offsetGet($offset);
    }
}
