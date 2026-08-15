<?php
namespace Apie\Core\Metadata\Strategy;

use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\StrategyInterface;
use Apie\Core\Metadata\UriMetadata;
use ReflectionClass;
use Uri\Rfc3986\Uri;

class UriStrategy implements StrategyInterface
{
    public static function supports(ReflectionClass $class): bool
    {
        return $class->name === Uri::class;
    }

    /**
     * @param ReflectionClass<Uri> $class
     */
    public function __construct(private readonly ReflectionClass $class)
    {
    }

    public function getCreationMetadata(ApieContext $context): UriMetadata
    {
        return new UriMetadata($this->class);
    }

    public function getModificationMetadata(ApieContext $context): UriMetadata
    {
        return new UriMetadata($this->class);
    }

    public function getResultMetadata(ApieContext $context): UriMetadata
    {
        return new UriMetadata($this->class);
    }
}