<?php
namespace Apie\DoctrineMetadataDriver\Decorators;

use Apie\Core\Actions\BoundedContextEntityTuple;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Creating entity for a main resource.
 */
class ApieResourceMetadata extends ClassMetadata
{
    public function __construct(string $apiePrefix, BoundedContextEntityTuple $boundedContextEntityTuple)
    {
        $entityName = $apiePrefix . $boundedContextEntityTuple->boundedContext->getId() . '\\' . $boundedContextEntityTuple->resourceClass->getShortName();
        parent::__construct(
            $entityName
        );
    }
}
