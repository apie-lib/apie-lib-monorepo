<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Common\Other\AuditLog;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\ObjectWithRelation;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieAuditLogMigrationCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_create_audit_log_mutations_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_create_audit_log_mutations'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_create_audit_log_mutations_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_create_audit_log_mutations(TestApplicationInterface $testApplication)
    {
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class) {
            $this->markTestSkipped('Faker will result in infinite loop here.');
        }
        $testApplication->bootApplication();
        $container = $testApplication->getServiceContainer();
        $datalayer = $container->get('apie');

        $id = PrimitiveOnlyIdentifier::createRandom();
        $datalayer->persistNew(
            new PrimitiveOnly($id),
            new BoundedContextId('types')
        );
        $datalayer->persistNew(
            new ObjectWithRelation(
                UserIdentifier::createRandom($container->get('apie.faker')),
                new DateTimeImmutable('+1 day')
            ),
            new BoundedContextId('types')
        );

        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:audit-log-for-migration',
        ]);
        $this->assertStringContainsString('PrimitiveOnly', $tester->getDisplay());
        $this->assertStringNotContainsString('ObjectWithRelation', $tester->getDisplay());
        
        $count = $datalayer->all(AuditLog::class, new BoundedContextId('types'))
            ->getTotalCount();
        $this->assertEquals(1, $count);
        $this->assertEquals(Command::SUCCESS, $exitCode, $tester->getDisplay());
        $testApplication->cleanApplication();
    }
}
