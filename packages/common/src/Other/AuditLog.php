<?php
namespace Apie\Common\Other;

use Apie\Core\Attributes\AlwaysDisabled;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Attributes\StoreOptions;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\IdFriendlyEntityReference;
use Apie\Serializer\PropertySerializer\SerializedProperties;
use Apie\Serializer\ValueObjects\SerializedPhpObject;

#[FakeCount(0)]
class AuditLog implements EntityInterface
{
    private AuditLogIdentifier $id;

    #[StaticCheck(new AlwaysDisabled())]
    public function __construct(
        private readonly IdFriendlyEntityReference $reference,
        #[Context()]
        private readonly SerializedPhpObject $serializedProperties,
    ) {
        $this->id = new AuditLogIdentifier($reference, microtime(true));
    }

    public function getId(): AuditLogIdentifier
    {
        return $this->id;
    }

    public function getReference(): IdFriendlyEntityReference
    {
        return $this->reference;
    }

    public function getData(): ?EntityInterface
    {
        $entity = $this->serializedProperties->toPhpObject();
        if ($entity instanceof EntityInterface) {
            return $entity;
        }
        return null;
    }
}
