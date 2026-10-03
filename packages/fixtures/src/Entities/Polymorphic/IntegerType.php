<?php
namespace Apie\Fixtures\Entities\Polymorphic;

class IntegerType extends MixedTypes
{
    public function __construct(
        protected MixedTypesIdentifier $id,
        public int $name,
        public int $value,
        public int $step,
        public ?int $nullableValue,
        public ?int $uniqueToInteger
    ) {
    }
}
