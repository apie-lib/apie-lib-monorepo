<?php
namespace Apie\Tests\StatusCheckPlugin\DataLayers;

use Apie\Core\Exceptions\InvalidClassTypeException;
use Apie\Core\Exceptions\ResourceNotFoundException;
use Apie\Core\SearchFilters\SearchFilterRequest;
use Apie\StatusCheckPlugin\ApiResources\Status;
use Apie\StatusCheckPlugin\DataLayers\StatusCheckRetriever;
use Apie\StatusCheckPlugin\StatusChecks\StaticStatusCheck;
use Apie\StatusCheckPlugin\StatusChecks\StatusCheckListInterface;
use ArrayIterator;
use PHPUnit\Framework\TestCase;

class StatusCheckRetrieverTest extends TestCase
{
    private $testItem;

    private $listCheck;

    protected function setUp(): void
    {
        $this->listCheck = $this->prophesize(StatusCheckListInterface::class);
        $this->listCheck->getIterator()
            ->willReturn(
                new ArrayIterator(
                    [
                        new StaticStatusCheck(new Status('from list check', 'OK', 'https://php.net', [])),
                        new Status('a status object', 'OK', 'https://php.net', []),
                    ]
                )
            );

        $statusChecks = [
            $this->listCheck->reveal(),
            new StaticStatusCheck(new Status('static test', 'OK', 'https://phpunit.de', []))
        ];
        $this->testItem = new StatusCheckRetriever($statusChecks);
    }

    public function testRetrieve()
    {
        $this->assertEquals(
            new Status('static test', 'OK', 'https://phpunit.de', []),
            $this->testItem->retrieve(Status::class, 'static test', [])
        );
    }

    public function testRetrieveAll_wrong_status_check()
    {
        $this->testItem = new StatusCheckRetriever([$this]);
        $actual = $this->testItem->retrieveAll(Status::class, [], new SearchFilterRequest(0, 10));
        $this->expectException(InvalidClassTypeException::class);
        iterator_to_array($actual);
    }

    public function testRetrieveAll_wrong_status_check_in_list()
    {
        $listItem = $this->prophesize(StatusCheckListInterface::class);
        $listItem->getIterator()->willReturn(
            new ArrayIterator([$this])
        );

        $this->testItem = new StatusCheckRetriever([$listItem->reveal()]);
        $actual = $this->testItem->retrieveAll(Status::class, [], new SearchFilterRequest(0, 10));
        $this->expectException(InvalidClassTypeException::class);
        iterator_to_array($actual);
    }

    public function testRetrieveAll()
    {
        $this->assertEquals(
            [
                new Status('from list check', 'OK', 'https://php.net', []),
                new Status('a status object', 'OK', 'https://php.net', []),
                new Status('static test', 'OK', 'https://phpunit.de', []),
            ],
            iterator_to_array($this->testItem->retrieveAll(Status::class, [], new SearchFilterRequest(0, 10)))
        );
    }

    public function testRetrieve_entry_not_found()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->testItem->retrieve(Status::class, 'not found', []);
    }
}
