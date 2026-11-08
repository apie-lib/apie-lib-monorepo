<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieLocalizationMethodCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    #[RunInSeparateProcess]
    #[Test]
    #[DataProvider('it_can_use_language_in_console_commands_provider')]
    public function it_can_use_language_in_console_commands(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:types:authentication:run:accept-locale'
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('en' . PHP_EOL, $tester->getDisplay());
        
        
        $testApplication->cleanApplication();
    }

    public static function it_can_use_language_in_console_commands_provider(): \Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_use_language_in_console_commands'),
            new IntegrationTestHelper()
        );
    }
}
