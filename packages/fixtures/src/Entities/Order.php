<?php
namespace Apie\Fixtures\Entities;

use Apie\Core\Entities\RootAggregate;
use Apie\Fixtures\Identifiers\OrderIdentifier;
use Apie\Fixtures\Identifiers\OrderLineIdentifier;
use Apie\Fixtures\Lists\OrderLineList;

class Order implements RootAggregate
{
    public function __construct(private OrderIdentifier $id, private OrderLineList $orderLineList)
    {
    }

    public function getId(): OrderIdentifier
    {
        return $this->id;
    }

    public function getOrderLines(): OrderLineList
    {
        return $this->orderLineList;
    }

    public function addOrderLine(OrderLine... $orderLines): int
    {
        foreach ($orderLines as $orderLine) {
            $this->orderLineList[] = $orderLine;
        }
        return count($this->orderLineList);
    }

    public function removeOrderLine(OrderLineIdentifier $id): ?int
    {
        $id = $id->toNative();
        foreach ($this->orderLineList as $key => $orderLine) {
            if ($orderLine->getId()->toNative() === $id) {
                unset($this->orderLineList[$key]);
                return $key;
            }
        }
        return null;
    }
}
