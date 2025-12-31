<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Common\IntegrationTestLogger;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\BootstrapRequestInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GraphqlProvider implements TestRequestInterface, BootstrapRequestInterface
{
    /**
     * @param array<string, mixed> $graphQlQuery
     * @param array<string, mixed> $expectedResponse
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        protected readonly array $graphQlQuery,
        protected array $expectedResponse
    ) {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        // TODO
    }

    public function shouldDoRequestValidation(): bool
    {
        return false;
    }

    public function shouldDoResponseValidation(): bool
    {
        return true;
    }

    public function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            'POST',
            'http://localhost/' . $this->boundedContextId . '/graphql',
            [
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ],
            json_encode($this->graphQlQuery)
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(200, $statusCode, 'Expect object created, got: ' . $body);
        $data = json_decode($body, true);
        $error = isset($data['errors']) ? json_encode($data['errors'], JSON_PRETTY_PRINT) : $body;
        if (IntegrationTestLogger::getLoggedException()) {
            IntegrationTestLogger::failTestShowError($statusCode);
        }
        TestCase::assertEquals($this->expectedResponse, $data, 'Expected response is not right, got: ' . $error);
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}