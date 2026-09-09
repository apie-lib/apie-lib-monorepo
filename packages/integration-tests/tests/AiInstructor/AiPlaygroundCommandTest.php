<?php
namespace Apie\Tests\IntegrationTests\AiInstructor;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class AiPlaygroundCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_run_a_playground_command_provider(): \Generator
    {
        yield from self::createDataProviderFrom(
            new \ReflectionMethod(__CLASS__, 'it_can_run_a_playground_command'),
            new \Apie\IntegrationTests\IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_run_a_playground_command_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_run_a_playground_command(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:ai-playground',
            '--system' => 'This is a test system prompt for the LLM.',
            '--user' => 'What is the capital of France?',
            '--model' => 'gpt-3.5-turbo',
            '--type' => 'string'
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $testApplication->cleanApplication();
    }
}
