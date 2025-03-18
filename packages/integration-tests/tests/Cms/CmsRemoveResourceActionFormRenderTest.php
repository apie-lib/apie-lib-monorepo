<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\Common\ApieFacade;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsRemoveResourceActionFormRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_renders_a_remove_resource_form_page_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_a_remove_resource_form_page'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_renders_a_remove_resource_form_page_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_renders_a_remove_resource_form_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        /** @var ApieFacade $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $apie->persistNew(
            new PrimitiveOnly(PrimitiveOnlyIdentifier::fromNative('550e8400-e29b-41d4-a716-446655440000')),
            new BoundedContextId('types')
        );
        $response = $testApplication->httpRequestGet('/cms/types/resource/delete/PrimitiveOnly/550e8400-e29b-41d4-a716-446655440000');
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertStringContainsString('Do you really want', (string) $response->getBody());
    }
}
