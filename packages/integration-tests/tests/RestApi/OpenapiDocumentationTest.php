<?php
namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\IntegrationTests\FixtureUtils;
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

    public function display_openapi_spec_in_yaml_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_display_openapi_spec_in_yaml'),
            new IntegrationTestHelper()
        );
    }

    public function it_can_display_the_swagger_ui_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_display_the_swagger_ui_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_display_the_swagger_ui_page_provider
     * @test
     */
    public function it_can_display_the_swagger_ui_page(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('swagger-ui.css', (string) $response->getBody());
        $testApplication->cleanApplication();
    }

    public function it_can_display_the_swagger_ui_redirect_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_display_the_swagger_ui_redirect_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_display_the_swagger_ui_redirect_page_provider
     * @test
     */
    public function it_can_display_the_swagger_ui_redirect_page(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/oauth2-redirect.html');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('OAuth2 Redirect', (string) $response->getBody());
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider display_openapi_spec_in_json_provider
     * @test
     */
    public function it_can_display_openapi_spec_in_json(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/openapi.json');
        $this->assertEquals(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $fixtureFile = FixtureUtils::getOpenapiFixtureFile($testApplication);

        // file_put_contents($fixtureFile, $body);
        $expected = file_get_contents($fixtureFile);
        $this->assertEquals($expected, $body);
        $testApplication->cleanApplication();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider display_openapi_spec_in_yaml_provider
     * @test
     */
    public function it_can_display_openapi_spec_in_yaml(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet(
            '/api/types/openapi.yaml'
        );
        $this->assertEquals(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $fixtureFile = FixtureUtils::getOpenapiFixtureFile($testApplication, false);

        // file_put_contents($fixtureFile, $body);
        $expected = file_get_contents($fixtureFile);
        $this->assertEquals($expected, $body);
        $testApplication->cleanApplication();
    }
}
