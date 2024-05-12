<?php
namespace Apie\Core\Dto;

/**
 * Used for metadata to show a limited number of options.
 */
final class ValueOption implements DtoInterface
{
    public readonly string $typehint;

    public function __construct(
        public readonly string $name,
        public readonly mixed $value
    ) {
        $this->typehint = get_debug_type($value);
    }
}
