<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use ReflectionClass;

/**
 * @implements IdentifierInterface<PrimitiveOnly>
 */
final class PrimitiveOnlyIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(PrimitiveOnly::class);
    }
}
