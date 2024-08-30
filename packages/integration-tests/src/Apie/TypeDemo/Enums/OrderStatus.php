<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Enums;

use LogicException;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case ACCEPTED = 'accepted';
    case DELIVERED = 'delivered';

    public function ensureDraft(): void
    {
        if ($this !== self::DRAFT) {
            throw new LogicException('Order status should be draft for this operation');
        }
    }
}
