<?php
namespace App\ApiePlayground\Types\Entities\Mammals;

use App\ApiePlayground\Types\Entities\Mammal;
use App\ApiePlayground\Types\Concerns\FlyingAnimal;

final class Bat extends Mammal
{
    use FlyingAnimal;
}