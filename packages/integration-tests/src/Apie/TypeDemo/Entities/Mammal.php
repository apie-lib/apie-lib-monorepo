<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;

abstract class Mammal extends Animal
{
    public function isCapableOfLayingEggs(): bool
    {
        return false;
    }

    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'name',
            new DiscriminatorConfig('elephant', Elephant::class),
            new DiscriminatorConfig('platypus', Platypus::class),
            new DiscriminatorConfig('human', Human::class),
        );
    }
}
