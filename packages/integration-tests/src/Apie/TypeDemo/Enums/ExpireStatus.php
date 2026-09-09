<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Enums;

use Apie\Core\Attributes\Description;

enum ExpireStatus: string
{
    #[Description('Object is still active and not expired yet')]
    case ACTIVE = 'active';
    #[Description('Object expire date has passed')]
    case EXPIRED = 'expired';
}
