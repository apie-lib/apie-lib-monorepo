<?php
namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\Common\IntegrationTestLogger;
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

    public static function display_openapi_spec_in_json_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_display_openapi_spec_in_json'),
            new IntegrationTestHelper()
        );
    }

    public static function display_openapi_spec_in_yaml_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_display_openapi_spec_in_yaml'),
            new IntegrationTestHelper()
        );
    }

    public static function it_can_display_the_swagger_ui_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_display_the_swagger_ui_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_display_the_swagger_ui_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_the_swagger_ui_page(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('swagger-ui.css', (string) $response->getBody());
        $testApplication->cleanApplication();
    }

    public static function it_can_display_the_swagger_ui_redirect_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_display_the_swagger_ui_redirect_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_display_the_swagger_ui_redirect_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_the_swagger_ui_redirect_page(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/oauth2-redirect.html');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('OAuth2 Redirect', (string) $response->getBody());
        $testApplication->cleanApplication();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('display_openapi_spec_in_json_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_openapi_spec_in_json(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/api/types/openapi.json');
        if ($response->getStatusCode() === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        $this->assertEquals(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $fixtureFile = FixtureUtils::getOpenapiFixtureFile($testApplication);

        file_put_contents($fixtureFile, $body);
        $expected = file_get_contents($fixtureFile);
        $this->assertEquals($expected, $body);
        $testApplication->cleanApplication();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('display_openapi_spec_in_yaml_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_openapi_spec_in_yaml(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet(
            '/api/types/openapi.yaml'
        );
        if ($response->getStatusCode() === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        $this->assertEquals(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $fixtureFile = FixtureUtils::getOpenapiFixtureFile($testApplication, false);

        file_put_contents($fixtureFile, $body);
        $expected = file_get_contents($fixtureFile);
        $this->assertEquals($expected, $body);
        $testApplication->cleanApplication();
    }
}
