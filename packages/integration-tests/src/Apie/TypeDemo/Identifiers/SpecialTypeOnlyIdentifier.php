<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\SpecialTypeOnly;
use ReflectionClass;

/**
 * @implements IdentifierInterface<SpecialTypeOnly>
 */
final class SpecialTypeOnlyIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(SpecialTypeOnly::class);
    }
}
