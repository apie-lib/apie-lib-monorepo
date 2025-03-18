<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

final class Platypus extends Mammal
{
    public function isCapableOfLayingEggs(): bool
    {
        return true;
    }
}
