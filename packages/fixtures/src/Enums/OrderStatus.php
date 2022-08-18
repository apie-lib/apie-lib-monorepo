<?php
namespace Apie\Fixtures\Enums;

use LogicException;

enum OrderStatus: string
{
    case DRAFT = 'DRAFT';
    case ACCEPTED = 'ACCEPTED';
    case COMPLETED = 'COMPLETED';

    public function ensureDraft(): void
    {
        if ($this !== self::DRAFT) {
            throw new LogicException('Order should be in draft status');
        }
    }
}
