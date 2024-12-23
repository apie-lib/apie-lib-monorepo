<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsDashboardTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_renders_a_dashboard_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_a_dashboard_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_a_dashboard_page_provider
     * @test
     */
    public function it_renders_a_dashboard_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/types/');
        $this->assertEquals(200, $response->getStatusCode());
        $expected = 'This is a the default dashboard template';
        if (!$testApplication->getApplicationConfig()->doesIncludeTemplating()) {
            $expected = 'To configure the dashboard, you require to include symfony/twig-bundle';
        }
        $this->assertStringContainsString($expected, (string) $response->getBody());
    }
}
