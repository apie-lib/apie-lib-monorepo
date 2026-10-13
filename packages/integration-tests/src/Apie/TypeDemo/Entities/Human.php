<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Attributes\Description;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;

#[Description('Humans have a last name and a first name described as animalName')]
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
