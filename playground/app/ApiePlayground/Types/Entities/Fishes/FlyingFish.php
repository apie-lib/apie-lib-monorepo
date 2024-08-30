<?php
namespace App\ApiePlayground\Types\Entities\Fishes;

use App\ApiePlayground\Types\Concerns\FlyingAnimal;
use App\ApiePlayground\Types\Concerns\SwimmingAnimal;
use App\ApiePlayground\Types\Entities\Fish;

final class FlyingFish extends Fish
{
    use SwimmingAnimal;
    use FlyingAnimal;
}