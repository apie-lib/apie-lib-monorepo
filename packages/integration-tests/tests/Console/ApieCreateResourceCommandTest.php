<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\Metadata\MetadataFactory;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
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

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_create_a_resource_provider
     * @test
     */
    public function it_can_create_a_resource(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run(['apie:types:create-PrimitiveOnly', '--input-id' => '075433c9-ca1f-435c-be81-61bae3009521']);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThanOrEqual(
            1,
            $testApplication->getServiceContainer()->get('apie')->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
        );
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_create_a_resource_provider
     * @test
     */
    public function it_can_create_a_resource_with_interaction(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $metadata = MetadataFactory::getCreationMetadata(new ReflectionClass(PrimitiveOnly::class), new ApieContext());
        $inputPerField = [
            'stringField' => [0, 'string'],
            'integerField' => [0, 42],
            'floatingPoint' => [0, 1.5],
            'booleanField' => [0, 'yes'],
            'id' => ['075433c9-ca1f-435c-be81-61bae3009521']
        ];
        $inputs = [];
        foreach ($metadata->getHashmap() as $key => $mapping) {
            $inputs = [...$inputs, ...$inputPerField[$key]];
        }
        $tester->setInputs($inputs);
        $exitCode = $tester->run(['apie:types:create-PrimitiveOnly', '--interactive' => true], ['interactive' => true]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $this->assertGreaterThanOrEqual(
            1,
            $testApplication->getServiceContainer()->get('apie')->all(PrimitiveOnly::class, new BoundedContextId('types'))->getTotalCount()
        );
        $testApplication->cleanApplication();
    }
}
