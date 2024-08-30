<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\Common\ApieFacade;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\SearchTerm;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Apie\Tests\ApieBundle\HtmlOutput;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CmsResourceOverviewRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_renders_a_resource_overview_page_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_a_resource_overview_page'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_a_resource_overview_page_provider
     * @test
     */
    public function it_renders_a_resource_overview_page(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->bootApplication();
        /** @var ApieFacade $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $apie->persistNew(
            new User(new UserIdentifier('test@example.com')),
            new BoundedContextId('types')
        );
        $response = $testApplication->httpRequestGet('/cms/types/resource/User');
        $this->assertEquals(200, $response->getStatusCode());
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $this->assertStringContainsString(
                'test@example.com',
                (string) $response->getBody(),
                'I expect to see test@example.com on the overview page'
            );
        }
    }

    public function it_renders_a_resource_overview_page_with_text_search_filter_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_renders_a_resource_overview_page_with_text_search_filter'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_renders_a_resource_overview_page_with_text_search_filter_provider
     * @test
     */
    public function it_renders_a_resource_overview_page_with_text_search_filter(
        TestApplicationInterface $testApplication,
        SearchTerm $searchTerm
    ) {
        $testApplication->bootApplication();
        /** @var ApieFacade $apie */
        $apie = $testApplication->getServiceContainer()->get('apie');
        $apie->persistNew(
            new User(new UserIdentifier('test@example.com')),
            new BoundedContextId('types')
        );
        $response = $testApplication->httpRequestGet('/cms/types/resource/User?search=' . $searchTerm);
        $this->assertEquals(200, $response->getStatusCode());
        HtmlOutput::writeHtml(__METHOD__, (string) $response->getBody());
        $this->assertNotSame(
            false,
            strpos((string) $response->getBody(), 'value="' . (string) $searchTerm . '"'),
            'search field is filled in'
        );
        if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
            $method = 'assertSame';
            $message = 'I expect the created user with test@example.com on the overview page is filtered.';
            if (!$searchTerm->hasSearchTerms()) {
                $method = 'assertNotSame';
                $message = 'I expect the created user with test@example.com on the overview page is not filtered.';
            }
            $this->$method(
                false,
                strpos((string) $response->getBody(), 'test@example.com'),
                $message
            );
        }
    }
}
