<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

final class Ostrich extends Bird
{
    public function isCapableOfFlying(): bool
    {
        return false;
    }
}
