<?php
namespace Apie\Tests\IntegrationTests\TypescriptClientBuilder;

use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ControllerTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_display_static_content_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_display_static_content'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_display_static_content_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_static_content(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/js/contents/es6/index.js');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('export function', $response->getBody());
        $this->assertEquals('application/javascript', $response->getHeaderLine('Content-Type'));
        $testApplication->cleanApplication();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_display_static_content_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_display_a_rendered_client(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/js/Apie.es6.js');
        $this->assertEquals(200, $response->getStatusCode());
        $expectedApiUrl = 'http://localhost/api';
        $this->assertStringContainsString("const apiUrl = " . json_encode($expectedApiUrl, JSON_UNESCAPED_SLASHES) . ";", $response->getBody());
        $this->assertStringContainsString('export const ApieLayer = createForApi(apiUrl, resourceDefinition);', $response->getBody());
        $this->assertEquals('application/javascript', $response->getHeaderLine('Content-Type'));
        $testApplication->cleanApplication();
    }

    public static function it_returns_404_on_missing_asset_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_returns_404_on_missing_asset'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_returns_404_on_missing_asset_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_404_on_missing_asset(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/js/contents/es6/missing.txt');
        $this->assertEquals(404, $response->getStatusCode());
        $testApplication->cleanApplication();
    }
}
