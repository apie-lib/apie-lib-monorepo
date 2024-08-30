<?php

namespace App\ApiePlayground\Permission\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use App\ApiePlayground\Permission\Resources\Customer;
use ReflectionClass;

class CustomerIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(Customer::class);
    }
}
