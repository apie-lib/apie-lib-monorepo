<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;

abstract class Fish extends Animal
{
    public function isCapableOfWalkingOnWater(): bool
    {
        return false;
    }

    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'name',
            new DiscriminatorConfig('shark', Shark::class),
            new DiscriminatorConfig('lungfish', Lungfish::class),
        );
    }
}
