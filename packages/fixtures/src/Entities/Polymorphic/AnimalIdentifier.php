<?php
namespace Apie\Fixtures\Entities\Polymorphic;

use Apie\CommonValueObjects\Identifiers\UuidV4;
use Apie\Core\Identifiers\IdentifierInterface;
use ReflectionClass;

class AnimalIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(Animal::class);
    }
}
