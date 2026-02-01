<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Common\IntegrationTestLogger;
use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\BootstrapRequestInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GraphqlProvider implements TestRequestInterface, BootstrapRequestInterface
{
    private bool $faked = false;
    /**
     * @param array<string, mixed> $graphQlQuery
     * @param array<string, mixed> $expectedResponse
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        protected readonly BoundedContextId $boundedContextId,
        protected readonly array $graphQlQuery,
        protected array $expectedResponse,
        protected array $entities = [],
    ) {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        /** @var ApieFacadeInterface $apieFacade */
        $apieFacade = $testApplication->getServiceContainer()->get('apie');
        foreach ($this->entities as $entity) {
            $apieFacade->persistNew($entity, $this->boundedContextId);
        }
        $this->faked = $testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class;
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
        if (!$this->faked) {
            TestCase::assertEquals($this->expectedResponse, $data, 'Expected response is not right, got: ' . $error);
        }
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}
