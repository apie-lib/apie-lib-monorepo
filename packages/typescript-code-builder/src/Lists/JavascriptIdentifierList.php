<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

final class JavascriptIdentifierList extends ItemList
{
    protected bool $mutable = false;

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof JavascriptIdentifier)) {
            $value = JavascriptIdentifier::fromNative($value);
        }
        parent::offsetSet($offset, $value);
    }

    public function offsetGet(mixed $offset): JavascriptIdentifier
    {
        return parent::offsetGet($offset);
    }

    /**
     * @return string[]
     */
    public function toStringArray(): array
    {
        $result = [];
        foreach ($this as $item) {
            $result[] = $item->toNative();
        }
        return $result;
    }
}
