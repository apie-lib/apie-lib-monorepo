<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Enums;

enum ExpireStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
}