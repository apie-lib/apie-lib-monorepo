<?php
namespace App\ApiePlayground\Types\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use App\ApiePlayground\Types\Entities\Mammals\FlyingFish;
use App\ApiePlayground\Types\Entities\Mammals\LungFish;
use App\ApiePlayground\Types\Entities\Mammals\Shark;
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
}