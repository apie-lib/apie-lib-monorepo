<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieRemoveResourceCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_remove_a_resource_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_remove_a_resource'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_remove_a_resource_provider
     * @test
     */
    public function it_can_remove_a_resource(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        /** @var ApieFacadeInterface $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $entity = new PrimitiveOnly(PrimitiveOnlyIdentifier::fromNative('075433c9-ca1f-435c-be81-61bae3009521'));
        $apie->persistNew($entity, new BoundedContextId('types'));
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:types:remove-PrimitiveOnly', 'id' => '075433c9-ca1f-435c-be81-61bae3009521']);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $this->assertEquals(
                0,
                $apie->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
            );
        }
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_remove_a_resource_provider
     * @test
     */
    public function it_returns_an_error_on_invalid_id(TestApplicationInterface $testApplication)
    {
        $invalidIds = [
            '075433c9-ca1f-435c-be81-61bae3009521',
            'invalid'
        ];
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class) {
            array_shift($invalidIds);
        }
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        foreach ($invalidIds as $invalidId) {
            $exitCode = $tester->run([
                'apie:types:remove-PrimitiveOnly',
                'id' => $invalidId
            ]);
            $this->assertEquals(Command::FAILURE, $exitCode, 'console command gave me ' . $tester->getDisplay());
        }
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_remove_a_resource_provider
     * @test
     */
    public function it_can_remove_a_resource_with_interaction(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        /** @var ApieFacadeInterface $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $entity = new PrimitiveOnly(PrimitiveOnlyIdentifier::fromNative('075433c9-ca1f-435c-be81-61bae3009521'));
        $apie->persistNew($entity, new BoundedContextId('types'));

        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:types:remove-PrimitiveOnly', 'id' => '075433c9-ca1f-435c-be81-61bae3009521', '--interactive' => true], ['interactive' => true]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThanOrEqual(
            0,
            $testApplication->getServiceContainer()->get('apie')->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
        );
        $testApplication->cleanApplication();
    }
}