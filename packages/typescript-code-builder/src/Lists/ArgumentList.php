<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;

class ArgumentList extends ItemList
{
    public function offsetGet(mixed $offset): FunctionArgument
    {
        return parent::offsetGet($offset);
    }

    public function toTypescript(): string
    {
        $list = [];
        foreach ($this as $argument) {
            $list[] = $argument->toTypescript();
        }
        return implode(', ', $list);
    }

    public function toJavascript(): string
    {
        $list = [];
        foreach ($this as $argument) {
            $list[] = $argument->toJavascript();
        }
        return implode(', ', $list);
    }
}