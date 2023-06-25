<?php
namespace Apie\DoctrineMetadataDriver;

use Apie\Common\ApieFacade;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\DoctrineMetadataDriver\Decorators\ApieResourceMetadata;
use Apie\DoctrineMetadataDriver\Builder\ApieMetadataBuilder;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

class ApieMetadataDriver implements MappingDriver
{
    private readonly string $apiePrefix;
    /**
     * @param array<int, ClassMetadata>
     */
    private array $builtMetadata;

    public function __construct(
        string $apiePrefix,
        private readonly ApieMetadataBuilder $builder,
        private readonly BoundedContextHashmap $boundedContextHashmap
    ) {
        $this->apiePrefix = rtrim($apiePrefix, '\\') . '\\';
    }
    /**
     * {@inheritDoc}
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        $this->getMetadata();
    }

    private function getMetadata(): array
    {
        if (!isset($this->builtMetadata)) {
            $this->builtMetadata = $this->builder->buildMetadata($this->boundedContextHashmap, $this->apiePrefix);
        }

        return $this->builtMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllClassNames()
    {
        $builtMetadata = $this->getMetadata();
        $classes = [];
        foreach ($builtMetadata as $metadata) {
            $classes[] = $metadata->rootEntityName;
        }
        return $classes;
    }

    /**
     * {@inheritDoc}
     */
    public function isTransient($className)
    {
        $builtMetadata = $this->getMetadata();
        foreach ($builtMetadata as $metadata) {
            if ($metadata->rootEntityName === $className) {
                return $metadata instanceof ApieResourceMetadata;
            }
        }
        return false;
    }
}