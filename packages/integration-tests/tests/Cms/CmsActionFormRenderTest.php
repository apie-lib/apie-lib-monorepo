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

    public function it_renders_an_action_form_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_an_action_form_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_an_action_form_page_provider
     * @test
     */
    public function it_renders_an_action_form_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/types/action/Authentication/verifyAuthentication');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('"username"', (string) $response->getBody());
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_an_action_form_page_provider
     * @test
     */
    public function it_refuses_invalid_csrf_tokens(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequestGet('/cms/types/action/Authentication/verifyAuthentication');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('"username"', (string) $response->getBody());
    }
}
