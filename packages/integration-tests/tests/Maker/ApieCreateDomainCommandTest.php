<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\Other\FileWriterInterface;
use Apie\Core\Other\MockFileWriter;
use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieCreateDomainCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_create_a_domain_object_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_create_a_domain_object'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_create_a_domain_object_provider
     * @test
     */
    public function it_can_create_a_domain_object(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $mockWriter = $testApplication->getServiceContainer()->get(FileWriterInterface::class);
        assert($mockWriter instanceof MockFileWriter);
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:create-domain-object', 'name' => 'TestObject']);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertCount(
            2,
            $mockWriter->writtenFiles
        );
        $testApplication->cleanApplication();
    }
}
