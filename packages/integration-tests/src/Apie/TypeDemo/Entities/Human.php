<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;

final class Human extends Mammal
{
    public function __construct(
        AnimalIdentifier $id,
        FirstName $animalName,
        private readonly LastName $lastName
    ) {
        parent::__construct($id, $animalName);
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }
}
