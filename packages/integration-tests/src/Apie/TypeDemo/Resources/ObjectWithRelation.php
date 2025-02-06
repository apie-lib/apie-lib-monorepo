<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\ApieLib;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Entities\RequiresRecalculatingInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\ObjectWithRelationIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\OrderIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use DateTimeInterface;

class ObjectWithRelation implements EntityInterface, RequiresRecalculatingInterface
{
    private ObjectWithRelationIdentifier $id;

    public function __construct(
        public UserIdentifier $userId,
        public DateTimeInterface $expireDate,
        private ?OrderIdentifier $orderId = null,
    ) {
        $this->id = ObjectWithRelationIdentifier::createRandom();
    }

    public function getId(): ObjectWithRelationIdentifier
    {
        return $this->id;
    }

    public function isExpired(): bool
    {
        return $this->expireDate < ApieLib::getPsrClock()->now();
    }

    public function getOrderId(): ?OrderIdentifier
    {
        if ($this->isExpired()) {
            return null;
        }
        return $this->orderId;
    }

    public function getDateToRecalculate(): ?DateTimeInterface
    {
        if ($this->isExpired()) {
            return null;
        }
        return $this->expireDate;
    }
}
