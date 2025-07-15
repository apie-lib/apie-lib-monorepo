<?php
namespace Apie\Fixtures\Entities\Polymorphic;

class StringType extends MixedTypes
{
    public function __construct(
        protected MixedTypesIdentifier $id,
        public string $name,
        public string $value,
        public string $step,
        public ?string $nullableValue,
        public ?string $uniqueToString
    ) {
    }
}
