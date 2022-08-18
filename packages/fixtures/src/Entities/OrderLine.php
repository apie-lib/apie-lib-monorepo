<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Fixtures\Identifiers\OrderLineIdentifier;

class OrderLine implements EntityInterface
{
    public function __construct(private OrderLineIdentifier $id)
    {
    }

    public function getId(): OrderLineIdentifier
    {
        return $this->id;
    }
}
