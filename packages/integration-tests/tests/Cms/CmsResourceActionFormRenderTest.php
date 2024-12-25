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

    public static function it_renders_a_resource_action_form_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_a_resource_action_form_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_renders_a_resource_action_form_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
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
        
        $this->assertStringContainsString('"blockedReason"', (string) $response->getBody());
    }

    public static function it_can_execute_a_resource_action_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_execute_a_resource_action'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_execute_a_resource_action_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_execute_a_resource_action(
        TestApplicationInterface $testApplication,
        CmsFormSubmitRequest $cmsFormSubmitRequest
    ) {
        $testApplication->bootApplication();
        $cmsFormSubmitRequest->bootstrap($testApplication);
        $response = $testApplication->httpRequest($cmsFormSubmitRequest);
        $cmsFormSubmitRequest->verifyValidResponse($response);
        $location = $response->getHeaderLine('location');
        $this->assertEquals($location, $cmsFormSubmitRequest->getExpectedTargetUrl());
    }
}
