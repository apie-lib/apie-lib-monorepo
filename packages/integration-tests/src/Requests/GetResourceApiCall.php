<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonSetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class GetResourceApiCall implements TestRequestInterface, BootstrapRequestInterface
{
    private bool $faked = false;
    /**
     * @param class-string<EntityInterface> $resourceName
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly string $resourceName,
        private readonly string $id,
        private readonly array $entities,
        private readonly JsonSetFieldInterface $inputOutput
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

    public function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            'GET',
            'http://localhost/api/' . $this->boundedContextId . '/' . (new ReflectionClass($this->resourceName))->getShortName() . '/' . $this->id,
            [
                'accept' => 'application/json',
            ]
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        TestCase::assertEquals(200, $statusCode, 'Expect object retrieved, got: ' . $body);
        $data = json_decode($body, true);
        if (!$this->faked) {
            $this->inputOutput->assertResponseValue($data);
        }
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}