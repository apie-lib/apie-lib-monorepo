<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\Common\ApieFacade;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\CmsFormSubmitRequest;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsResourceActionFormRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_renders_a_resource_action_form_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_a_resource_action_form_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_a_resource_action_form_page_provider
     * @test
     */
    public function it_renders_a_resource_action_form_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        /** @var ApieFacade $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $apie->persistNew(
            new User(new UserIdentifier('test@example.com')),
            new BoundedContextId('types')
        );
        $response = $testApplication->httpRequestGet('/cms/types/resource/action/User/test@example.com/block');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('form[blockedReason]', (string) $response->getBody());
    }

    public function it_can_execute_a_resource_action_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_execute_a_resource_action'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_execute_a_resource_action_provider
     * @test
     */
    public function it_can_execute_a_resource_action(
        TestApplicationInterface $testApplication,
        CmsFormSubmitRequest $cmsFormSubmitRequest
    ) {
        $testApplication->bootApplication();
        $cmsFormSubmitRequest->bootstrap($testApplication);
        $response = $testApplication->httpRequest($cmsFormSubmitRequest);
        $cmsFormSubmitRequest->verifyValidResponse($response);
    }
}
