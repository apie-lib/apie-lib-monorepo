<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\Other\FileWriterInterface;
use Apie\Core\Other\MockFileWriter;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieMakeTranslationFileCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_create_translation_file_in_php_format_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_create_translation_file_in_php_format'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_create_translation_file_in_php_format_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_create_translation_file_in_php_format(TestApplicationInterface $testApplication)
    {
        $this->runCommandTest(
            $testApplication,
            '/tmp/example.php',
            __DIR__ . '/../../fixtures/translation.phpinc'
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_create_translation_file_in_php_format_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_create_translation_file_in_json_format(TestApplicationInterface $testApplication)
    {
        $this->runCommandTest(
            $testApplication,
            '/tmp/example.json',
            __DIR__ . '/../../fixtures/translation.json'
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_create_translation_file_in_php_format_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_create_translation_file_in_yaml_format(TestApplicationInterface $testApplication)
    {
        $this->runCommandTest(
            $testApplication,
            '/tmp/example.yaml',
            __DIR__ . '/../../fixtures/translation.yaml'
        );
        $this->runCommandTest(
            $testApplication,
            '/tmp/example.yml',
            __DIR__ . '/../../fixtures/translation.yaml'
        );
    }

    private function runCommandTest(
        TestApplicationInterface $testApplication,
        string $outputFile,
        string $fixtureFile
    ) {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:make-translation-file',
            'filename' => $outputFile,
        ]);
        $this->assertStringContainsString('Created ' . $outputFile . ' successfully', $tester->getDisplay());
        $this->assertEquals(Command::SUCCESS, $exitCode);

        $filewriter = $testApplication->getServiceContainer()->get(FileWriterInterface::class);
        $this->assertInstanceOf(MockFileWriter::class, $filewriter);
        $this->assertArrayHasKey($outputFile, $filewriter->writtenFiles);
        file_put_contents($fixtureFile, $filewriter->writtenFiles[$outputFile]);
        $this->assertEquals(
            file_get_contents($fixtureFile),
            $filewriter->writtenFiles[$outputFile]
        );

        $testApplication->cleanApplication();
    }
}
