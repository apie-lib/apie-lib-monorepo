<?php
namespace Apie\Tests\Maker\Fixtures\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Tests\Maker\Fixtures\Resources\AlreadyExistingResource;
use ReflectionClass;

/**
 * This is the original resource without modifications
 */
class AlreadyExistingResourceIdentifier extends PascalCaseSlug implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(AlreadyExistingResource::class);
    }
}
