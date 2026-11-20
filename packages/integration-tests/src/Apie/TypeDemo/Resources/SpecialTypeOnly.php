<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\Auditable;
use Apie\Core\Attributes\ClassStoreOptions;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Enums\SortingOrder;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\SpecialTypeOnlyIdentifier;
use BcMath\Number;
use GMP;

#[Auditable(readAllEvents: true)]
#[RemovalCheck(new StaticCheck())]
#[ClassStoreOptions(defaultSortingOrder: SortingOrder::Ascending)]
final class SpecialTypeOnly implements EntityInterface
{
    public ?Number $bcmathNumber = null;

    public ?GMP $gmp = null;

    public function __construct(
        private SpecialTypeOnlyIdentifier $id
    ) {
    }

    public function getId(): SpecialTypeOnlyIdentifier
    {
        return $this->id;
    }
}
