<?php
namespace App\ApiePlayground\Types\Entities\Mammals;

use App\ApiePlayground\Types\Concerns\SwimmingAnimal;
use App\ApiePlayground\Types\Entities\Fish;

final class Shark extends Fish
{
    use SwimmingAnimal;
}