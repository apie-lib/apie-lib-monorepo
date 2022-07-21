<?php
namespace Apie\Fixtures\Entities\Polymorphic;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use ReflectionClass;

class AnimalIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(Animal::class);
    }
}
