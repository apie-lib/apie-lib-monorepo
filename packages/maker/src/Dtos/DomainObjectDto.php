<?php
namespace Apie\Maker\Dtos;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Dto\DtoInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Maker\Enums\IdType;

final class DomainObjectDto implements DtoInterface
{
    public function __construct(
        public PascalCaseSlug $name,
        public BoundedContextId $boundedContextId,
        public IdType $idType,
        public bool $reuseExistingCode = true,
    ) {
    }
}
