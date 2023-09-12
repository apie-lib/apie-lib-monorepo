<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;

final class PrimitiveOnly implements EntityInterface
{
    public ?string $stringField = null;

    public ?int $integerField = null;

    public ?float $floatingPoint = null;

    public ?bool $booleanField = null;

    public function __construct(
        private readonly PrimitiveOnlyIdentifier $id
    ) {
    }

    public function getId(): PrimitiveOnlyIdentifier
    {
        return $this->id;
    }
}
