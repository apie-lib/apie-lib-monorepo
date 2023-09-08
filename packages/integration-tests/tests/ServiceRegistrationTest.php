<?php
namespace Apie\Tests\IntegrationTests;

use Apie\Common\ApieFacade;
use Apie\IntegrationTests\ExampleClass;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ServiceRegistrationTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_registers_an_apie_service_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_registers_an_apie_service'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @dataProvider it_registers_an_apie_service_provider
     * @test
     */
    public function it_registers_an_apie_service(TestApplicationInterface $testApplication)
    {
        $this->markTestIncomplete();
        $testApplication->bootApplication();
        $apieService = $testApplication->getServiceContainer()->get('apie');
        $this->assertInstanceOf(ApieFacade::class, $apieService);
        $testApplication->cleanApplication();
    }
}