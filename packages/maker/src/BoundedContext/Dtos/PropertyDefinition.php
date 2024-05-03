<?php
namespace Apie\Maker\BoundedContext\Dtos;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Identifiers\CamelCaseSlug;
use Apie\Maker\Enums\PrimitiveType;
use Apie\Maker\ValueObjects\VendorValueObject;

final class PropertyDefinition implements DtoInterface
{
    public function __construct(
        public VendorValueObject|PrimitiveType $type,
        public CamelCaseSlug $name,
        public bool $requiredOnConstruction,
        public bool $writable,
        public bool $readable
    ) {
    }
}
