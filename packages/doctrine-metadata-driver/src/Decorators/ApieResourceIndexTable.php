<?php
namespace Apie\DoctrineMetadataDriver\Decorators;

use Apie\Core\BoundedContext\BoundedContext;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ClassMetadata;

class ApieResourceIndexTable extends ClassMetadata
{
    public function __construct(string $apiePrefix, BoundedContext $boundedContext)
    {
        $entityName = $apiePrefix . $boundedContext->getId() . '\\ApieInternalIndexTable';
        parent::__construct(
            $entityName
        );
        $this->identifier = ['id'];
        $this->fieldMappings['id'] = [
            'fieldName' => 'id',
            'type' => Types::INTEGER,
            'id' => true,
            'notUpdatable' => true,
        ];
        $this->generatorType = self::GENERATOR_TYPE_AUTO;
    }
}
