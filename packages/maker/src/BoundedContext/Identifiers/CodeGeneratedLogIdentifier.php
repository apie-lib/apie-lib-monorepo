<?php

namespace Apie\Maker\BoundedContext\Identifiers;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\Maker\BoundedContext\Resources\CodeGeneratedLog;
use ReflectionClass;

class CodeGeneratedLogIdentifier extends Ulid implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(CodeGeneratedLog::class);
    }
}
