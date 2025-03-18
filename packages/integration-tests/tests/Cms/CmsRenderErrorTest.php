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

    public static function it_renders_an_error_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_an_error_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_renders_an_error_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_renders_an_error_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/does-not-exist/');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('An error occurred. Please try again later.', (string) $response->getBody());
    }
}
