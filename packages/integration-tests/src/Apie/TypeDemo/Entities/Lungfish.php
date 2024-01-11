<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

final class Lungfish extends Fish
{
    public function isCapableOfWalkingOnWater(): bool
    {
        return true;
    }
}
