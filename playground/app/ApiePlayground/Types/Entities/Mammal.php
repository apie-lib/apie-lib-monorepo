<?php
namespace App\ApiePlayground\Types\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use App\ApiePlayground\Types\Entities\Mammals\Bat;
use App\ApiePlayground\Types\Entities\Mammals\Dolphin;
use App\ApiePlayground\Types\Entities\Mammals\Elephant;
use App\ApiePlayground\Types\Entities\Mammals\Human;
use App\ApiePlayground\Types\Resources\Animal;

abstract class Mammal extends Animal
{
    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'mammalType',
            new DiscriminatorConfig(
                'elephant',
                Elephant::class
            ),
            new DiscriminatorConfig(
                'dolphin',
                Dolphin::class
            ),
            new DiscriminatorConfig(
                'human',
                Human::class
            ),
            new DiscriminatorConfig(
                'bat',
                Bat::class
            )
        );
    }
}