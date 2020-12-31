<?php


namespace Apie\OpenapiSchema\Map;


use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\Spec\PathItem;

final class CallbackPathItemList extends \ArrayObject implements \JsonSerializable
{
    public function offsetSet($index, $newval)
    {
        $index = (string) $index;
        if (!preg_match(Constants::VALID_EXPRESSION_REGEX, $index)) {
            throw new \InvalidArgumentException('Index is not a valid key name for a CallbackPathItemList');
        }
        if (!$newval instanceof PathItem) {
            throw new \InvalidArgumentException('Argument should be an instance of PathItem');
        }
        parent::offsetSet($index, $newval);
    }

    public function jsonSerialize()
    {
        $list = $this->getArrayCopy();
        if (empty($list)) {
            return new \stdClass();
        }
        return $list;
    }
}