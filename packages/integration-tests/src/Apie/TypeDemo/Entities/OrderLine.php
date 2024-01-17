<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\OrderLineIdentifier;
use Apie\TextValueObjects\NonEmptyString;
use App\Apie\Example\Identifiers\OrderLineId;

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
