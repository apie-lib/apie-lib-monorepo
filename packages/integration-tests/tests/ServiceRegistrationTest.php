<?php
namespace Apie\Tests\IntegrationTests;

use Apie\Common\Actions\GetItemAction;
use Apie\Common\ApieFacade;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\LaravelApie\Apie;
use Apie\LaravelApie\ErrorHandler\ApieErrorRenderer;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Exception;
use Generator;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class ServiceRegistrationTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_registers_an_apie_service_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_registers_an_apie_service'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_registers_an_apie_service_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_registers_an_apie_service(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        $apieService = $testApplication->getServiceContainer()->get('apie');
        $this->assertInstanceOf(ApieFacade::class, $apieService);
        $testApplication->cleanApplication();
    }

    public static function it_registers_an_error_render_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_registers_an_error_render'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_registers_an_error_render_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_registers_an_error_render(LaravelTestApplication $testApplication)
    {
        $testApplication->bootApplication();
        $errorRenderer = $testApplication->getServiceContainer()->get(ApieErrorRenderer::class);
        $this->assertInstanceOf(ApieErrorRenderer::class, $errorRenderer);
        $this->assertInstanceOf(Response::class, $errorRenderer->createCmsResponse(new Request(), new Exception('hi everybody!')));
        $this->assertInstanceOf(Response::class, $errorRenderer->createApiResponse(new Exception('hi dr. Nick!')));
        $testApplication->cleanApplication();
    }

    public static function it_registers_a_laravel_facade_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_registers_a_laravel_facade'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_registers_a_laravel_facade_provider')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[WithoutErrorHandler]
    public function it_registers_a_laravel_facade(LaravelTestApplication $testApplication)
    {
        // no idea why this test does something weird with the error handler
        // it gives error: 'Test code or tested code removed error handlers other than its own'
        set_error_handler(null);
        try {
            $testApplication->bootApplication();
            $apieService = $testApplication->getServiceContainer()->get('apie');
            $this->assertInstanceOf(
                GetItemAction::class,
                Apie::createAction(new ApieContext([ContextConstants::APIE_ACTION => GetItemAction::class]))
            );
            $this->assertSame($apieService, Apie::getFacadeRoot());
            $testApplication->cleanApplication();
        } finally {
            restore_error_handler();
        }
    }
}
