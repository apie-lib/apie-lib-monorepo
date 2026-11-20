<?php

namespace Apie\ApieBundle\DataCollector\FieldData;

class ObjectType extends AbstractFieldData
{
    public function __construct(
        object $value
    ) {
        $this->data = (string) spl_object_id($value);
        $this->typehint = get_debug_type($value);
    }

    public function __toString(): string
    {
        return 'Object(' . $this->typehint . ')';
    }
}
