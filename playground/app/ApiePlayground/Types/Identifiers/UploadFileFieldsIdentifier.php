<?php

namespace App\ApiePlayground\Types\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;
use App\ApiePlayground\Types\Resources\UploadFileFields;
use ReflectionClass;

class UploadFileFieldsIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UploadFileFields::class);
    }
}
