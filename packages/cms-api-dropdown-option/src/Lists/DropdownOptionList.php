<?php
namespace Apie\CmsApiDropdownOption\Lists;

use Apie\CmsApiDropdownOption\Dtos\DropdownOption;
use Apie\Core\Lists\ItemList;

class DropdownOptionList extends ItemList
{
    public function offsetGet(mixed $offset): DropdownOption
    {
        return parent::offsetGet($offset);
    }
}
