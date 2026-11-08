<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\ApieLib;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\Search\QuerySearch;
use Apie\Core\Lists\StringHashmap;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Enums\ExpireStatus;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\ObjectWithRelation;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Beste\Clock\FrozenClock;
use Faker\Factory;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieUpdateRecalculatingCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_update_idf_indexing_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_update_idf_indexing'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_update_idf_indexing_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_update_idf_indexing(TestApplicationInterface $testApplication)
    {
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class) {
            $this->markTestSkipped('Faker will result in infinite loop here.');
        }
        $testApplication->bootApplication();
        $container = $testApplication->getServiceContainer();
        $fixedTime = new \DateTimeImmutable('+1 day');
        ApieLib::setPsrClock(FrozenClock::at($fixedTime));
        $datalayer = $container->get('apie');
        $entity = new ObjectWithRelation(
            userId: UserIdentifier::createRandom(Factory::create()),
            expireDate: new \DateTimeImmutable('+1 day')
        );
        $datalayer->persistNew(
            $entity,
            new BoundedContextId('types')
        );
        $fixedTime = new \DateTimeImmutable('+2 day');
        ApieLib::setPsrClock(FrozenClock::at($fixedTime));
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:recalculate-resources',
        ]);
        $this->assertStringContainsString('ObjectWithRelation', $tester->getDisplay());
        $this->assertStringContainsString($entity->getId() . ' Done', $tester->getDisplay());
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $newEntity = $datalayer->all(ObjectWithRelation::class, new BoundedContextId('types'))
            ->toPaginatedResult(
                new QuerySearch(
                    0,
                    20,
                    null,
                    new StringHashmap([
                        'status' => ExpireStatus::EXPIRED->value,
                    ])
                )
            )
            ->getIterator()
            ->current();
        $this->assertEquals($entity->getId(), $newEntity->getId());
        $testApplication->cleanApplication();
    }
}
