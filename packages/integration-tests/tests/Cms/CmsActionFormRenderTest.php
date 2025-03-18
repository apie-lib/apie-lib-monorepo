<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsActionFormRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_renders_an_action_form_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_an_action_form_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_renders_an_action_form_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_renders_an_action_form_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/types/action/Authentication/verifyAuthentication');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('"username"', (string) $response->getBody());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_renders_an_action_form_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_refuses_invalid_csrf_tokens(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/types/action/Authentication/verifyAuthentication');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('"username"', (string) $response->getBody());
    }
}
