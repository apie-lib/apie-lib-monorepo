<?php
namespace App\ApiePlayground\Types\Entities\Mammals;

use App\ApiePlayground\Types\Entities\Mammal;

final class Elephant extends Mammal
{
    private int $drankWater = 0;

    public function isThirsty(): bool
    {
        return $this->drankWater < 5;
    }

    public function drink(int $amount): void
    {
        $this->drankWater += $amount;
    }

    public function spoutWater(int $amount): void
    {
        $this->drankWater = max(0, $this->drankWater - $amount);
    }
}