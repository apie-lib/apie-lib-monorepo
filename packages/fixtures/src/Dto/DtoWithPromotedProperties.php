<?php

namespace Apie\Fixtures\Dto;

use Apie\Core\Dto\DtoInterface;

class DtoWithPromotedProperties implements DtoInterface
{
    public function __construct(
        public ?string $name = null,
        public ?string $value = null
    ) {
    }
}
