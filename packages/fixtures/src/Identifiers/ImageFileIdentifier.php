<?php
namespace Apie\Fixtures\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use Apie\Fixtures\Entities\ImageFile;
use ReflectionClass;

/**
 * @implements IdentifierInterface<ImageFile>
 */
final class ImageFileIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ImageFile::class);
    }
}
