<?php
namespace Apie\Fixtures\Lists;

use Apie\Core\Lists\ItemList;
use Apie\TextValueObjects\StrongPassword;

class StrongPasswordList extends ItemList
{
    public function offsetGet($offset): StrongPassword
    {
        return parent::offsetGet($offset);
    }
}
