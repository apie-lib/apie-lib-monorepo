<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\OrderLineIdentifier;

class OrderLine implements EntityInterface
{
    private OrderLineIdentifier $id;

    public function __construct(private NonEmptyString $description)
    {
        $this->id = OrderLineIdentifier::createRandom();
    }

    public function getId(): OrderLineIdentifier
    {
        return $this->id;
    }

    public function getDescription(): NonEmptyString
    {
        return $this->description;
    }
}
