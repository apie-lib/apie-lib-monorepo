<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\Common\ApieFacade;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsResourceRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_renders_a_resource_detail_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_a_resource_detail_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_a_resource_detail_page_provider
     * @test
     */
    public function it_renders_a_resource_detail_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        /** @var ApieFacade $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $apie->persistNew(
            new User(new UserIdentifier('test@example.com')),
            new BoundedContextId('types')
        );
        $response = $testApplication->httpRequestGet('/cms/types/resource/User/test@example.com');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('test@example.com', (string) $response->getBody());
    }
}