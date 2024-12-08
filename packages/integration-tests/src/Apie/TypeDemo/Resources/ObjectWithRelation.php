<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\ObjectWithRelationIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\OrderIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;

class ObjectWithRelation implements EntityInterface
{
    private ObjectWithRelationIdentifier $id;

    public function __construct(
        public UserIdentifier $userId,
        public ?OrderIdentifier $orderId = null
    ) {
        $this->id = ObjectWithRelationIdentifier::createRandom();
    }

    public function getId(): ObjectWithRelationIdentifier
    {
        return $this->id;
    }
}
