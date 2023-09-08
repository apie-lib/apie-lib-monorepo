<?php
namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\IntegrationTests\ExampleClass;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class OpenapiDocumentationTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function display_openapi_spec_in_json_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_display_openapi_spec_in_json'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @dataProvider display_openapi_spec_in_json_provider
     * @test
     */
    public function it_can_display_openapi_spec_in_json(TestApplicationInterface $testApplication)
    {
        $this->markTestIncomplete();
        $testItem = new ExampleClass();
        $this->assertEquals('Salami', $testItem->getPizza());
    }
}