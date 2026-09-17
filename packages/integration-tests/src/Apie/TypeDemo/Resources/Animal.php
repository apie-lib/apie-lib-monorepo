<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Bird;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Fish;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Mammal;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\Serializer\Lists\SerializedHashmap;
use Apie\TextValueObjects\FirstName;

#[ExampleValue(
    example: new SerializedHashmap([
        'id' => '123e4567-e89b-12d3-a456-426614174000',
        'animalName' => 'Charlie',
        'name' => 'elephant',
        'type' => 'mammal'
    ]),
    name: 'Example elephant'
)]
#[ExampleValue(
    example: new SerializedHashmap([
        'id' => '123e4567-e89b-12d3-a456-426614174001',
        'animalName' => 'Polly',
        'name' => 'ostrich',
        'type' => 'bird'
    ]),
    name: 'Example ostrich'
)]
#[ExampleValue(
    example: new SerializedHashmap([
        'id' => '123e4567-e89b-12d3-a456-426614174002',
        'animalName' => 'Nemo',
        'name' => 'shark',
        'type' => 'fish'
    ]),
    name: 'Example fish'
)]
abstract class Animal implements PolymorphicEntityInterface
{
    public function __construct(
        protected AnimalIdentifier $id,
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
