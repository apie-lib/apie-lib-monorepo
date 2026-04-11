<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\Auditable;
use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\OverwriteAfterPersist;
use Apie\Core\Attributes\RemovalCheck;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\OrderLine;
use Apie\IntegrationTests\Apie\TypeDemo\Enums\OrderStatus;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\OrderIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Lists\OrderLineList;
use Apie\IntegrationTests\Apie\TypeDemo\Rules\OnlyDraftOrders;

#[RemovalCheck(new StaticCheck())]
#[RemovalCheck(new RuntimeCheck(new OnlyDraftOrders()))]
#[FakeCount(1)]
#[Auditable()]
#[Description('An order is a web order and consists of multiple order lines')]
#[OverwriteAfterPersist]
class Order implements EntityInterface
{
    private OrderIdentifier $id;

    private OrderStatus $orderStatus;

    public function __construct(private OrderLineList $orderLineList)
    {
        $this->id = new OrderIdentifier(null);
        $this->orderStatus = OrderStatus::DRAFT;
    }

    public function getId(): OrderIdentifier
    {
        return $this->id;
    }

    public function getOrderStatus(): OrderStatus
    {
        return $this->orderStatus;
    }

    public function getOrderLineList(): OrderLineList
    {
        return $this->orderLineList;
    }

    #[RuntimeCheck(new OnlyDraftOrders())]
    public function addOrderLine(OrderLine $orderLine): Order
    {
        $this->orderStatus->ensureDraft();
        $this->orderLineList[] = $orderLine;

        return $this;
    }
}
