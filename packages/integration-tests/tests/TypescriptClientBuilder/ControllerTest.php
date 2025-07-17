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
        $response = $testApplication->httpRequestGet('/contents/es6/index');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('export function', $response->getBody());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $testApplication->cleanApplication();
    }
}