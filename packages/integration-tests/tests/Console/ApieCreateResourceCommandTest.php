<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Console\InteractiveConsoleCommand;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieCreateResourceCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_create_a_resource_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_create_a_resource'),
            new IntegrationTestHelper()
        );
    }

    public function it_can_create_a_resource_with_interaction_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_create_a_resource_with_interaction'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_create_a_resource_provider
     * @test
     */
    public function it_can_create_a_resource(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:types:primitive-only:create',
            '--input-id' => '075433c9-ca1f-435c-be81-61bae3009521'
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThanOrEqual(
            1,
            $testApplication->getServiceContainer()->get('apie')->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
        );
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_create_a_resource_with_interaction_provider
     * @test
     */
    public function it_can_create_a_resource_with_interaction(
        TestApplicationInterface $testApplication,
        InteractiveConsoleCommand $interactiveConsoleCommand
    ) {
        $this->runInteractiveConsoleTest(
            $testApplication,
            $interactiveConsoleCommand->command,
            new ReflectionClass($interactiveConsoleCommand->class),
            $interactiveConsoleCommand->getInputs()
        );
    }

    private function runInteractiveConsoleTest(
        TestApplicationInterface $testApplication,
        string $command,
        ReflectionClass $class,
        array $inputs
    ): void {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());

        $tester->setInputs($inputs);
        $exitCode = $tester->run([$command, '--interactive' => true], ['interactive' => true]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThanOrEqual(
            1,
            $testApplication->getServiceContainer()
                ->get('apie')
                ->all($class->name, new BoundedContextId('types'))
                ->getTotalCount()
        );
        $testApplication->cleanApplication();
    }
}
