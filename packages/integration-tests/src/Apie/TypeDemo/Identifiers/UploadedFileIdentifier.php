<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\UploadedFile;
use ReflectionClass;

/**
 * @implements IdentifierInterface<UploadedFile>
 */
final class UploadedFileIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UploadedFile::class);
    }
}
