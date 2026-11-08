<?php

namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\IntegrationTests\ErrorRenderTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\BootstrapRequestInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * Regression test verifying that erroring API calls result in the correct HTTP status code
 * (401/403 for authorization errors, 422 for validation errors, 404 for missing resources)
 * instead of a generic 500.
 */
class ErrorRenderTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_renders_the_correct_error_status_code_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_renders_the_correct_error_status_code'),
            new ErrorRenderTestHelper()
        );
    }

    #[DataProvider('it_renders_the_correct_error_status_code_provider')]
    #[RunInSeparateProcess]
    #[Test]
    public function it_renders_the_correct_error_status_code(
        TestApplicationInterface $testApplication,
        TestRequestInterface $testRequest
    ) {
        $testApplication->bootApplication();
        if ($testRequest instanceof BootstrapRequestInterface) {
            $testRequest->bootstrap($testApplication);
        }
        $response = $testApplication->httpRequest($testRequest);
        $testRequest->verifyValidResponse($response);
        $testApplication->cleanApplication();
    }
}
