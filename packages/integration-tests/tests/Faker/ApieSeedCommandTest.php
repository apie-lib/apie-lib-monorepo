<?php
namespace Apie\Tests\IntegrationTests\Faker;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieSeedCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_seed_apie_resources_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_seed_apie_resources'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_seed_apie_resources_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_seed_apie_resources(TestApplicationInterface $testApplication)
    {
        ini_set('memory_limit', -1);
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:seed-entities']);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThan(
            0,
            $testApplication->getServiceContainer()->get('apie')->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
        );
        $testApplication->cleanApplication();
    }
}
