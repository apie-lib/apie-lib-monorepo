<?php
namespace Apie\Tests\ApieFileSystem;

use Apie\ApieFileSystem\ApieFilesystemFactory;
use Apie\ApieFileSystem\Virtual\GetResourceListPaginationFolder;
use Apie\ApieFileSystem\Virtual\GetSingleResourceFile;
use Apie\ApieFileSystem\Virtual\RootFolder;
use Apie\ApieFileSystem\Virtual\VirtualFolderInterface;
use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Datalayers\InMemory\InMemoryDatalayer;
use Apie\Core\Datalayers\Search\LazyLoadedListFilterer;
use Apie\Core\Indexing\Indexer;
use Apie\Fixtures\BoundedContextFactory;
use Apie\Fixtures\Entities\Polymorphic\AnimalIdentifier;
use Apie\Fixtures\Entities\Polymorphic\Elephant;
use Apie\Serializer\Serializer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApieFilesystemFactoryTest extends TestCase
{
    private function createRange(int $start, int $end): array
    {
        $result = [];
        for ($i = $start; $i < $end; $i++) {
            $result[$this->toUuid($i) . '.json'] = GetSingleResourceFile::class;
        }

        return $result;
    }

    private function toUuid(int $i): string
    {
        return sprintf('%08d-0000-0000-0000-000000000000', $i);
    }
    #[Test]
    public function it_can_create_a_file_system_from_apie()
    {
        $factory = new ApieFilesystemFactory(
            new ActionDefinitionProvider(),
            BoundedContextFactory::createHashmapWithMultipleContexts()
        );
        $filter = new LazyLoadedListFilterer(Indexer::create());
        $dataLayer = new InMemoryDatalayer(new BoundedContextId('other'), $filter);
        for ($i = 10; $i < (GetResourceListPaginationFolder::ITEMS_PER_PAGE + 30); $i++) {
            $dataLayer->persistNew(new Elephant(AnimalIdentifier::fromNative($this->toUuid($i))));
        }
        $actual = $factory->create(new ApieContext([
            ApieDatalayer::class => $dataLayer,
            Serializer::class => Serializer::create(),
        ]));
        $this->assertInstanceOf(RootFolder::class, $actual->rootFolder);
        $this->assertSameStructure([
            'default' => [
                'resources' => [
                    'UserWithAddress' => [],
                    'Order' => [],
                ]
            ],
            'other' => [
                'resources' => [
                    'UserWithAutoincrementKey' => [],
                    'Animal' => [
                        '0' => $this->createRange(10, 10 + GetResourceListPaginationFolder::ITEMS_PER_PAGE),
                        '1' => $this->createRange(10 + GetResourceListPaginationFolder::ITEMS_PER_PAGE, 30 + GetResourceListPaginationFolder::ITEMS_PER_PAGE),
                    ],
                ]
            ],
        ], $actual->rootFolder);
    }

    public function assertSameStructure(array $expected, VirtualFolderInterface $folder): void
    {
        $children = $folder->getChildren();
        foreach ($expected as $name => $child) {
            $this->assertArrayHasKey($name, $children, 'Found keys: ' . implode(', ', array_keys($children->toArray())));
            $childItem = $children[$name];
            $this->assertEquals($name, $childItem->getName());
            if (is_array($child)) {
                $this->assertInstanceOf(VirtualFolderInterface::class, $childItem);
                $this->assertSameStructure($child, $childItem);
            } else {
                $this->assertInstanceOf($child, $childItem);
            }
        }
        $this->assertCount(count($expected), $children, 'Found keys: ' . implode(', ', array_keys($children->toArray())));
    }
}
