<?php

namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\FixtureUtils;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\ActionMethodApiCall;
use Apie\IntegrationTests\Requests\BootstrapRequestInterface;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionMethod;

class DoApiCallTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_run_a_documented_api_call_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_run_a_documented_api_call'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_run_a_documented_api_call_provider
     * @test
     */
    public function it_can_run_a_documented_api_call(
        TestApplicationInterface $testApplication,
        TestRequestInterface $testRequest
    ) {
        $testApplication->bootApplication();
        if ($testRequest instanceof BootstrapRequestInterface) {
            $testRequest->bootstrap($testApplication);
        }
        $response = $testApplication->httpRequest($testRequest);
        $testRequest->verifyValidResponse($response);
        $this->validateOpenApiSpec(
            $testApplication,
            $testRequest->getRequest(),
            $response,
            $testRequest->shouldDoRequestValidation(),
            $testRequest->shouldDoResponseValidation()
        );
        $testApplication->cleanApplication();
    }

    private function validateOpenApiSpec(
        TestApplicationInterface $testApplication,
        RequestInterface $psrRequest,
        ResponseInterface $psrResponse,
        bool $doRequestValidation,
        bool $doResponseValidation
    ) {
        $validatorBuilder = (new ValidatorBuilder)
            ->fromJsonFile(FixtureUtils::getOpenapiFixtureFile($testApplication));
        $requestValidator = $validatorBuilder->getRequestValidator();
        $responseValidator = $validatorBuilder->getResponseValidator();

        if ($doResponseValidation) {
            $operation = new OperationAddress(
                $psrRequest->getUri()->getPath(),
                strtolower($psrRequest->getMethod())
            );
            $responseValidator->validate($operation, $psrResponse);
        }

        if ($psrResponse->getStatusCode() < 300 && $doRequestValidation) {
            $requestValidator->validate($psrRequest);
        }
    }

    public function it_throws_validation_error_on_invalid_route_placeholders_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_throws_validation_error_on_invalid_route_placeholders'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_throws_validation_error_on_invalid_route_placeholders_provider
     * @test
     */
    public function it_throws_validation_error_on_invalid_route_placeholders(
        TestApplicationInterface $testApplication
    ) {
        $testRequest = new ActionMethodApiCall(
            new BoundedContextId('types'),
            'calc/not-valid/plus/12',
            new GetPrimitiveField('', 13)
        );
        $testApplication->bootApplication();
        if ($testRequest instanceof BootstrapRequestInterface) {
            $testRequest->bootstrap($testApplication);
        }
        $response = $testApplication->httpRequest($testRequest);
        $this->assertEquals(422, $response->getStatusCode());
        $this->validateOpenApiSpec(
            $testApplication,
            $testRequest->getRequest(),
            $response,
            false,
            $testRequest->shouldDoResponseValidation()
        );
        $testApplication->cleanApplication();
    }
}
