<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\TranslationAware;
use ReflectionClass;

/**
 * @implements IdentifierInterface<TranslationAware>
 */
class TranslationAwareIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(TranslationAware::class);
    }
}
