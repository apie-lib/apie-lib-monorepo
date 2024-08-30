<?php
namespace App\ApiePlayground\Example\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use App\ApiePlayground\Example\Resources\UploadedFile;
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
