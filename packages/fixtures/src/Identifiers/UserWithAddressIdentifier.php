<?php
namespace Apie\Fixtures\Identifiers;

use Apie\CommonValueObjects\Identifiers\UuidV4;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Fixtures\Entities\UserWithAddress;
use ReflectionClass;

class UserWithAddressIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UserWithAddress::class);
    }
}
