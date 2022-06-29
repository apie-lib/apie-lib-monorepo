<?php
namespace Apie\Fixtures\Identifiers;

use Apie\Core\Identifiers\AutoIncrementInteger;
use Apie\Core\Identifiers\IdentifierInterface;
use ReflectionClass;

class UserAutoincrementIdentifier extends AutoIncrementInteger implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UserWithAutoincrementKey::class);
    }
}
