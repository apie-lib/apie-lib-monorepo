<?php
namespace Apie\Tests\IntegrationTests\DoctrineEntityDatalayer;

use Apie\ApieBundle\Doctrine\MergedRegistry;
use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class DoctrineConsoleCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_run_doctrine_schema_update_commands_on_apie_connection_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_run_doctrine_schema_update_commands_on_apie_connection'),
            new IntegrationTestHelper()
        );
    }

    public static function it_can_run_doctrine_schema_validate_commands_on_apie_connection_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_run_doctrine_schema_validate_commands_on_apie_connection'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_doctrine_schema_update_commands_on_apie_connection_provider
     * @test
     */
    public function it_can_run_doctrine_schema_update_commands_on_apie_connection(
        SymfonyTestApplication $testApplication
    ) {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['doctrine:schema:update', '--em' => MergedRegistry::APIE_MANAGER_NAME]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_doctrine_schema_validate_commands_on_apie_connection_provider
     * @test
     */
    public function it_can_run_doctrine_schema_validate_commands_on_apie_connection(
        SymfonyTestApplication $testApplication
    ) {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['doctrine:schema:update', '--em' => MergedRegistry::APIE_MANAGER_NAME]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $testApplication->cleanApplication();
    }
}
