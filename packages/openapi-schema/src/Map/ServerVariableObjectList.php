<?php


namespace Apie\OpenapiSchema\Map;

use Apie\OpenapiSchema\Constants;
use Apie\OpenapiSchema\Spec\ServerVariable;

final class ServerVariableObjectList extends \ArrayObject implements \JsonSerializable
{
    public function offsetSet($index, $newval) {
        $index = (string) $index;
        if (!preg_match(Constants::VALID_KEY_REGEX, $index)) {
            throw new \InvalidArgumentException('Index is not a valid key name for a ServerVariableObjectList');
        }
        if (!$newval instanceof ServerVariable) {
            throw new \InvalidArgumentException('Argument should be an instance of ServerVariableObject');
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