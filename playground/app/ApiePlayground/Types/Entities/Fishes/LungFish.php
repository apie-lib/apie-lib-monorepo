<?php
namespace App\ApiePlayground\Types\Entities\Fishes;

use App\ApiePlayground\Types\Concerns\SwimmingAnimal;
use App\ApiePlayground\Types\Entities\Fish;

final class LungFish extends Fish
{
    use SwimmingAnimal;
}