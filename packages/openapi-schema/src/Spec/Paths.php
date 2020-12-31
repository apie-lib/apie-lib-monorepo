<?php

namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;

class Paths extends \ArrayObject
{
    private $extensions;

    public function __construct($input = array())
    {
        $this->extensions = new SpecificationExtension([]);
        parent::__construct([]);
        foreach ($input as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function offsetSet($index, $newval) {
        $index = (string) $index;
        if (substr($index, 0, 1) === '/') {
            if (!$newval instanceof PathItem) {
                throw new \InvalidArgumentException('Argument should be an instance of PathItemObject');
            }
            parent::offsetSet($index, $newval);
            return;
        }
        $this->extensions = $this->extensions->withField($index, $newval);
    }

    public function jsonSerialize()
    {
        $copy = array_merge($this->getArrayCopy(), $this->extensions->toNative());
        if (empty($copy)) {
            return new \stdClass();
        }
        return $copy;
    }
}