<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonGetFieldInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonSetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class ValidCreateResourceApiCall implements TestRequestInterface
{
    /**
     * @param class-string<EntityInterface> $resourceName
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly string $resourceName,
        private readonly JsonGetFieldInterface&JsonSetFieldInterface $inputOutput
    ) {
    }

    public function getRequest(): ServerRequestInterface
    {
        $data = $this->inputOutput->getInputValue();
        return new ServerRequest(
            'POST',
            'http://localhost/api/' . $this->boundedContextId . '/' . (new ReflectionClass($this->resourceName))->getShortName(),
            [
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ],
            json_encode($data)
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        TestCase::assertEquals(201, $statusCode, 'Expect object created, got: ' . $body);
        $data = json_decode($body, true);
        $this->inputOutput->assertResponseValue($data);
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}
