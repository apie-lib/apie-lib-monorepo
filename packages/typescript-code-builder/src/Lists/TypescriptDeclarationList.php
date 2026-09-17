<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

class TypescriptDeclarationList extends ItemList
{
    public function offsetGet(mixed $offset): TypescriptTypeDeclarationInterface
    {
        return parent::offsetGet($offset);
    }
}
