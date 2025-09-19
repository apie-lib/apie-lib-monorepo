<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Not;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\Route;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Indexing\Indexer as IndexingIndexer;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\EntityReference;

class Indexer
{
    public function __construct(private readonly IndexingIndexer $indexer)
    {
    }

    #[StaticCheck(new Not(new Requires(ContextConstants::MCP_SERVER)))]
    #[Route(routeDefinition: '/indexes/{resourceName}/{resourceId}')]
    public function getAllIndexes(
        #[Context()]
        EntityReference $reference,
        #[Context()]
        ApieContext $apieContext
    ): StringSet
    {
        $object = $reference->resolve($apieContext);
        $indexes = $this->indexer->getIndexesFor($object, $apieContext);

        return new StringSet(array_keys($indexes));
    }
}