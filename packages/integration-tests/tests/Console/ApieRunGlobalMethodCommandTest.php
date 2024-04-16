<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\MetadataFactory;
use Apie\IntegrationTests\Apie\TypeDemo\Actions\Calculator;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieRunGlobalMethodCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_run_a_global_method_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_run_a_global_method'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_global_method_provider
     * @test
     */
    public function it_can_run_a_global_method(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:types:calculator:run:square-root',
            '--input-numberOne' => 4
        ]);
        // TODO display return value
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_global_method_provider
     * @test
     */
    public function it_can_run_a_global_method_with_interaction(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        
        $metadata = MetadataFactory::getMethodMetadata(new ReflectionMethod(Calculator::class, 'squareRoot'), new ApieContext());
        $inputPerField = [
            'numberOne' => [4],
        ];
        $inputs = [];
        foreach ($metadata->getHashmap() as $key => $mapping) {
            $inputs = [...$inputs, ...$inputPerField[$key]];
        }
        $tester->setInputs($inputs);
        $exitCode = $tester->run(['apie:types:calculator:run:square-root', '--interactive' => true], ['interactive' => true]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $testApplication->cleanApplication();
    }
}
