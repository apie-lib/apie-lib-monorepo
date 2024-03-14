<?php
namespace App\ApiePlayground\Types\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use App\ApiePlayground\Types\Entities\Fishes\FlyingFish;
use App\ApiePlayground\Types\Entities\Fishes\LungFish;
use App\ApiePlayground\Types\Entities\Fishes\Shark;
use App\ApiePlayground\Types\Resources\Animal;

abstract class Fish extends Animal
{
    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'fishType',
            new DiscriminatorConfig(
                'lungfish',
                LungFish::class,
            ),
            new DiscriminatorConfig(
                'shark',
                Shark::class,
            ),
            new DiscriminatorConfig(
                'flyfish',
                FlyingFish::class,
            )
        );
    }

    public function isThirsty(): bool
    {
        return false;
    }
}