<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Rules;

use Apie\Core\Attributes\ApieContextAttribute;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\IntegrationTests\Apie\TypeDemo\Enums\OrderStatus;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Order;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class OnlyDraftOrders implements ApieContextAttribute
{
    public function applies(ApieContext $context): bool
    {
        $resource = $context->getContext(ContextConstants::RESOURCE, false);
        if (!$resource) {
            return true; // resource is not loaded yet
        }
        if ($resource instanceof Order) {
            return $resource->getOrderStatus() === OrderStatus::DRAFT;
        }
        return false;
    }
}
