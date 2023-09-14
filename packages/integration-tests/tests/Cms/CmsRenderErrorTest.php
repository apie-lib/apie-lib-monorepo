<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsRenderErrorTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_renders_an_error_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_an_error_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_an_error_page_provider
     * @test
     */
    public function it_renders_an_error_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/does-not-exist/');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('An error occurred. Please try again later.', (string) $response->getBody());
    }
}
