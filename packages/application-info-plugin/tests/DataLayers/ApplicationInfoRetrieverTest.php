<?php
namespace Apie\Tests\ApplicationInfoPlugin\DataLayers;

use Apie\ApplicationInfoPlugin\ApiResources\ApplicationInfo;
use Apie\ApplicationInfoPlugin\DataLayers\ApplicationInfoRetriever;
use Apie\Core\SearchFilters\SearchFilterRequest;
use PHPUnit\Framework\TestCase;

class ApplicationInfoRetrieverTest extends TestCase
{
    public function testRetrieve()
    {
        $testItem = new ApplicationInfoRetriever('Unit test app', 'test', 'hash-123', true);
        $this->assertEquals(
            new ApplicationInfo('Unit test app', 'test', 'hash-123', true),
            $testItem->retrieve(ApplicationInfo::class, 'name', [])
        );
    }

    public function testRetrieveAll()
    {
        $testItem = new ApplicationInfoRetriever('Unit test app', 'test', 'hash-123', true);
        $actual = $testItem->retrieveAll(ApplicationInfo::class, [], new SearchFilterRequest());
        $this->assertEquals(
            [new ApplicationInfo('Unit test app', 'test', 'hash-123', true)],
            $actual
        );
    }
}
