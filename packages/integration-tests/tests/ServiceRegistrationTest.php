<?php
namespace Apie\Tests\IntegrationTests;

use Apie\Common\Actions\GetItemAction;
use Apie\Common\ApieFacade;
use Apie\Common\ContextConstants;
use Apie\Core\Context\ApieContext;
use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\LaravelApie\Apie;
use Apie\LaravelApie\ErrorHandler\ApieErrorRenderer;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Exception;
use Generator;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class ServiceRegistrationTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_registers_an_apie_service_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_registers_an_apie_service'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_registers_an_apie_service_provider
     * @test
     */
    public function it_registers_an_apie_service(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $apieService = $testApplication->getServiceContainer()->get('apie');
        $this->assertInstanceOf(ApieFacade::class, $apieService);
        $testApplication->cleanApplication();
    }

    public function it_registers_an_error_render_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_registers_an_error_render'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @dataProvider it_registers_an_error_render_provider
     * @test
     */
    public function it_registers_an_error_render(LaravelTestApplication $testApplication)
    {
        $testApplication->bootApplication();
        $errorRenderer = $testApplication->getServiceContainer()->get(ApieErrorRenderer::class);
        $this->assertInstanceOf(ApieErrorRenderer::class, $errorRenderer);
        $this->assertInstanceOf(Response::class, $errorRenderer->createCmsResponse(new Request(), new Exception('hi everybody!')));
        $this->assertInstanceOf(Response::class, $errorRenderer->createApiResponse(new Exception('hi dr. Nick!')));
        $testApplication->cleanApplication();
    }

    public function it_registers_a_laravel_facade_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_registers_a_laravel_facade'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @dataProvider it_registers_a_laravel_facade_provider
     * @test
     */
    public function it_registers_a_laravel_facade(LaravelTestApplication $testApplication)
    {
        $testApplication->bootApplication();
        $apieService = $testApplication->getServiceContainer()->get('apie');
        $this->assertInstanceOf(
            GetItemAction::class,
            Apie::createAction(new ApieContext([ContextConstants::APIE_ACTION => GetItemAction::class]))
        );
        $this->assertSame($apieService, Apie::getFacadeRoot());
        $testApplication->cleanApplication();
    }
}
