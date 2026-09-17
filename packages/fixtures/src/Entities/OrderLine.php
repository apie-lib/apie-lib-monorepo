<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\Price;
use Apie\Fixtures\Identifiers\OrderLineIdentifier;

class OrderLine implements EntityInterface
{
    public function __construct(private OrderLineIdentifier $id, private Price $price)
    {
    }

    public function getId(): OrderLineIdentifier
    {
        return $this->id;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
