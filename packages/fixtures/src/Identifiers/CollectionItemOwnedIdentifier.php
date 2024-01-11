<?php
namespace Apie\Fixtures\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\Fixtures\Entities\CollectionItemOwned;
use ReflectionClass;

class CollectionItemOwnedIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(CollectionItemOwned::class);
    }
}
