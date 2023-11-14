<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApieModifyResourceCommandTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_modify_a_resource_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_modify_a_resource'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_modify_a_resource_provider
     * @test
     */
    public function it_can_modify_a_resource(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        /** @var ApieFacadeInterface $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $entity = new PrimitiveOnly(PrimitiveOnlyIdentifier::fromNative('075433c9-ca1f-435c-be81-61bae3009521'));
        $apie->persistNew($entity, new BoundedContextId('types'));
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:types:modify-PrimitiveOnly', 'id' => '075433c9-ca1f-435c-be81-61bae3009521', '--input-stringField' => 'string']);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $this->assertEquals(
                'string',
                $apie->find($entity->getId(), new BoundedContextId('types'))->stringField
            );

        }
        $testApplication->cleanApplication();
    }
}
