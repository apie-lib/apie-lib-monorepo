<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Requests\JsonFields\JsonGetFieldInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonSetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Variant of ActionMethodApiCall used to verify that a request results in one of a set
 * of expected (error) status codes instead of the hardcoded 200 the parent class expects.
 *
 * Supports a configurable HTTP method so it can also be used to verify a GET request on a
 * non existing resource results in a 404 instead of only verifying action (POST) calls.
 */
class ActionMethodApiCallExpectingStatusCodes extends ActionMethodApiCall
{
    /**
     * @param array<int, int> $expectedStatusCodes
     */
    public function __construct(
        BoundedContextId $boundedContextId,
        string $url,
        JsonGetFieldInterface|JsonSetFieldInterface $inputOutput,
        private readonly array $expectedStatusCodes,
        private readonly string $method = 'POST',
    ) {
        parent::__construct($boundedContextId, $url, $inputOutput);
    }

    public function getRequest(): ServerRequestInterface
    {
        if ($this->method === 'POST') {
            return parent::getRequest();
        }
        return new ServerRequest(
            $this->method,
            'http://localhost/api/' . $this->boundedContextId . '/' . $this->url,
            [
                'accept' => 'application/json',
            ]
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        // the faker datalayer always returns a fake result, so authorization/not-found checks can not be guaranteed
        $expectedStatusCodes = $this->isFakeDatalayer()
            ? [...$this->expectedStatusCodes, 200]
            : $this->expectedStatusCodes;
        TestCase::assertContains(
            $statusCode,
            $expectedStatusCodes,
            'Expected one of status codes [' . implode(', ', $expectedStatusCodes) . '], got ' . $statusCode . ': ' . $body
        );
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}
