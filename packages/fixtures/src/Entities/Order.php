<?php
namespace Apie\Fixtures\Entities;

use APie\Core\Attributes\Internal;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\Entities\RootAggregate;
use Apie\Fixtures\Enums\OrderStatus;
use Apie\Fixtures\Identifiers\OrderIdentifier;
use Apie\Fixtures\Identifiers\OrderLineIdentifier;
use Apie\Fixtures\Lists\OrderLineList;

#[ProvideIndex('provideIndex')]
class Order implements RootAggregate
{
    private OrderStatus $orderStatus;

    public function __construct(private OrderIdentifier $id, private OrderLineList $orderLines)
    {
        $this->orderStatus = OrderStatus::DRAFT;
    }

    public function getOrderStatus(): OrderStatus
    {
        return $this->orderStatus;
    }

    #[Internal()]
    public function provideIndex(): array
    {
        $result = [];
        $result[$this->id->toNative()] = 2;
        foreach ($this->orderLines as $orderLine) {
            $result[$orderLine->getId()->toNative()] = 1;
        }
        return $result;
    }

    public function getId(): OrderIdentifier
    {
        return $this->id;
    }

    public function getOrderLines(): OrderLineList
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine... $orderLines): int
    {
        $this->orderStatus->ensureDraft();
        foreach ($orderLines as $orderLine) {
            $this->orderLines[] = clone $orderLine;
        }
        return count($this->orderLines);
    }

    public function removeOrderLine(OrderLineIdentifier $id): ?int
    {
        $this->orderStatus->ensureDraft();
        $id = $id->toNative();
        foreach ($this->orderLines as $key => $orderLine) {
            if ($orderLine->getId()->toNative() === $id) {
                unset($this->orderLines[$key]);
                return $key;
            }
        }
        return null;
    }

    public function acceptOrder(): void
    {
        $this->orderStatus->ensureDraft();
        $this->orderStatus = OrderStatus::ACCEPTED;
    }
}
