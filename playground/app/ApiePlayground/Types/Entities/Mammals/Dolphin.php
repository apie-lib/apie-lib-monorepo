<?php
namespace App\ApiePlayground\Types\Entities\Mammals;

use App\ApiePlayground\Types\Concerns\SwimmingAnimal;
use App\ApiePlayground\Types\Entities\Mammal;

final class Dolphin extends Mammal
{
    use SwimmingAnimal;
}