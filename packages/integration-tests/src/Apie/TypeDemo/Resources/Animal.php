<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Bird;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Fish;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Mammal;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\TextValueObjects\FirstName;

abstract class Animal implements PolymorphicEntityInterface
{
    public function __construct(
        private AnimalIdentifier $id,
        private FirstName $animalName,
    ) {
    }

    final public function getId(): AnimalIdentifier
    {
        return $this->id;
    }

    final public function setAnimalName(FirstName $animalName): self
    {
        $this->animalName = $animalName;
        return $this;
    }

    final public function getAnimalName(): FirstName
    {
        return $this->animalName;
    }

    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'type',
            new DiscriminatorConfig('mammal', Mammal::class),
            new DiscriminatorConfig('bird', Bird::class),
            new DiscriminatorConfig('fish', Fish::class)
        );
    }
}
