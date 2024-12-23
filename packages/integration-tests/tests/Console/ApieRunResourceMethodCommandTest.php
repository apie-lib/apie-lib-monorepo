<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\MetadataFactory;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieRunResourceMethodCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_run_a_resource_method_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_run_a_resource_method'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_resource_method_provider
     * @test
     */
    public function it_can_run_a_resource_method(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        /** @var ApieFacadeInterface $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $entity = new User(UserIdentifier::fromNative('info@example.com'));
        $apie->persistNew($entity, new BoundedContextId('types'));
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'apie:types:user:run:block',
            'id' => 'info@example.com',
            '--input-blockedReason' => 'string'
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $this->assertTrue(
                $apie->find($entity->getId(), new BoundedContextId('types'))->isBlocked()
            );
        }
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_resource_method_provider
     * @test
     */
    public function it_returns_an_error_on_invalid_id(TestApplicationInterface $testApplication)
    {
        $invalidIds = [
            'info@example.com',
            'invalid'
        ];
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class) {
            array_shift($invalidIds);
        }
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());

        foreach ($invalidIds as $invalidId) {
            $exitCode = $tester->run([
                'apie:types:user:run:block',
                'id' => $invalidId,
                '--input-blockedReason' => 'string'
            ]);
            $this->assertEquals(Command::FAILURE, $exitCode, 'console command gave me ' . $tester->getDisplay());
        }
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_resource_method_provider
     * @test
     */
    public function it_can_run_a_resource_method_with_interaction(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        /** @var ApieFacadeInterface $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $entity = new User(UserIdentifier::fromNative('info@example.com'));
        $apie->persistNew($entity, new BoundedContextId('types'));
        $metadata = MetadataFactory::getMethodMetadata(new ReflectionMethod(User::class, 'block'), new ApieContext());
        $inputPerField = [
            'blockedReason' => [0, 'string'],
        ];
        $inputs = [];
        foreach ($metadata->getHashmap() as $key => $mapping) {
            $inputs = [...$inputs, ...$inputPerField[$key]];
        }
        $tester->setInputs($inputs);
        $exitCode = $tester->run(['apie:types:user:run:block', 'id' => 'info@example.com', '--interactive' => true], ['interactive' => true]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $this->assertTrue(
                $apie->find($entity->getId(), new BoundedContextId('types'))->isBlocked()
            );
        }
        $testApplication->cleanApplication();
    }
}
