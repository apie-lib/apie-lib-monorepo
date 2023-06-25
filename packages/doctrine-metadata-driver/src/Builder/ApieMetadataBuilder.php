<?php
namespace Apie\DoctrineMetadataDriver\Builder;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\DoctrineMetadataDriver\Decorators\ApieResourceIndexTable;
use Apie\DoctrineMetadataDriver\Decorators\ApieResourceMetadata;

final class ApieMetadataBuilder
{
    public function __construct()
    {
    }

    /**
     * @return ClassMetadataInfo
     */
    public function buildMetadata(BoundedContextHashmap $boundedContextHashmap, string $apiePrefix = 'ApieDoctrine\\Generated\\'): array
    {
        $list = [];
        foreach ($boundedContextHashmap as $boundedContext) {
            $list[] = new ApieResourceIndexTable($apiePrefix, $boundedContext);
        }
        foreach ($boundedContextHashmap->getTupleIterator() as $tuple) {
            $list[] = new ApieResourceMetadata($apiePrefix, $tuple);
        }
        return $list;
    }
}