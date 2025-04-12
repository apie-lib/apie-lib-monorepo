<?php
namespace Apie\Core\BackgroundProcess;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Core\Identifiers\Ulid;
use Apie\Core\ValueObjects\SnowflakeIdentifier;
use ReflectionClass;

class SequentialBackgroundProcessIdentifier extends SnowflakeIdentifier implements IdentifierInterface
{
    public function __construct(
        private PascalCaseSlug $className,
        private Ulid $ulid
    ) {
        $this->toNative();
    }

    protected static function getSeparator(): string
    {
        return ',';
    }

    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(SequentialBackgroundProcess::class);
    }
}
