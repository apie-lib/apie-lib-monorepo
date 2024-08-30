<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\InMemory\InMemoryDatalayer;
use Apie\Core\Datalayers\Search\LazyLoadedListFilterer;
use Apie\Core\Indexing\Indexer;

trait TestWithInMemoryDatalayer
{
    private function givenAnInMemoryDataLayer(?BoundedContextId $boundedContextId = null): InMemoryDatalayer
    {
        return new InMemoryDatalayer(
            $boundedContextId ?? new BoundedContextId('unknown'),
            new LazyLoadedListFilterer(Indexer::create())
        );
    }
}
