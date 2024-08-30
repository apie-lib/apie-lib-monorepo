<?php
namespace Apie\Tests\Maker\BoundedContext\Resources;

use Apie\Core\ApieLib;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\InMemory\InMemoryDatalayer;
use Apie\Core\Datalayers\Search\LazyLoadedListFilterer;
use Apie\Core\Indexing\Indexer;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Core\Other\FileReaderInterface;
use Apie\Core\Other\FileWriterInterface;
use Apie\Core\Other\MockFileWriter;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\BoundedContext\Resources\BoundedContextDefinition;
use Apie\Maker\BoundedContext\Resources\CodeGeneratedLog;
use Apie\Maker\BoundedContext\Resources\ResourceDefinition;
use Apie\Maker\BoundedContext\Services\CodeWriter;
use Apie\Maker\Enums\OverwriteStrategy;
use Beste\Clock\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class CodeGeneratedLogTest extends TestCase
{
    use TestWithFaker;
    /**
     * @test
     */
    public function it_works_as_intended()
    {
        ApieLib::setPsrClock(FrozenClock::at(new DateTimeImmutable('1970-01-01')));
        $list = new ReflectionClassList([
            new ReflectionClass(ResourceDefinition::class),
            new ReflectionClass(BoundedContextDefinition::class),
        ]);
        $testItem = new CodeGeneratedLog(
            OverwriteStrategy::Merge,
            new InMemoryDatalayer(new BoundedContextId('maker'), new LazyLoadedListFilterer(Indexer::create())),
            new BoundedContext('maker', $list, new ReflectionMethodList()),
            new CodeWriter(new MockFileWriter()),
            ['target_path' => '/app', 'target_namespace' => 'App\Generated']
        );
        $this->assertNotNull($testItem->getId());
        $this->assertEquals('1970-01-01', $testItem->getDate()->format('Y-m-d'));
        $this->assertEquals(OverwriteStrategy::Merge, $testItem->getOverwriteStrategy());
        $this->assertNull($testItem->getErrorMessage());
        $this->assertNull($testItem->getErrorStacktrace());
        $this->assertEquals('/app', $testItem->getTargetPath());
        $this->assertEquals('App\Generated', $testItem->getTargetNamespace());
    }

    /**
     * @test
     */
    public function it_logs_errors()
    {
        ApieLib::setPsrClock(FrozenClock::at(new DateTimeImmutable('1970-01-01')));
        $filewriter = new class implements FileReaderInterface, FileWriterInterface {
            public function fileExists(string $filePath): bool
            {
                return false;
            }
            public function readContents(string $filePath): string
            {
                throw new \LogicException('I can not read');
            }
            public function clearPath(string $path): void
            {
                throw new \LogicException('I can not clear');
            }
            public function writeFile(string $filename, string $fileContents): void
            {
                throw new \LogicException("I can not write");
            }
        };
        $testItem = new CodeGeneratedLog(
            OverwriteStrategy::Reset,
            new InMemoryDatalayer(new BoundedContextId('test'), new LazyLoadedListFilterer(Indexer::create())),
            new BoundedContext('maker', new ReflectionClassList(), new ReflectionMethodList()),
            new CodeWriter($filewriter),
            ['target_path' => '/app', 'target_namespace' => 'App\Generated']
        );
        $this->assertEquals('I can not clear', $testItem->getErrorMessage());
        $this->assertNotNull($testItem->getErrorStacktrace());
    }

    /**
     * @test
     */
    public function it_does_not_work_with_faker()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('I can not create a random instance of CodeGeneratedLog');
        $this->runFakerTest(CodeGeneratedLog::class);
    }
}
