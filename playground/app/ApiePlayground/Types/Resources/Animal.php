<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use App\ApiePlayground\Types\Entities\Fish;
use App\ApiePlayground\Types\Entities\Mammal;
use App\ApiePlayground\Types\Identifiers\AnimalIdentifier;

class Animal implements PolymorphicEntityInterface
{
    private AnimalIdentifier $id;

    public function __construct()
    {
        $this->id = AnimalIdentifier::createRandom();
    }

    final public function getId(): AnimalIdentifier
    {
        return $this->id;
    }

    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'animalType',
            new DiscriminatorConfig(
                'mammal',
                Mammal::class
            ),
            new DiscriminatorConfig(
                'fish',
                Fish::class
            )
        );
    }
}
