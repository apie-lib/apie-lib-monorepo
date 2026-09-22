<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\IntegrationTestLogger;
use Apie\IntegrationTests\Requests\JsonFields\JsonGetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RemoveResourceApiCall extends ActionMethodApiCall
{
    public function getRequest(): ServerRequestInterface
    {
        $data = $this->inputOutput instanceof JsonGetFieldInterface ? $this->inputOutput->getInputValue() : [];
        return new ServerRequest(
            'DELETE',
            'http://localhost/api/' . $this->boundedContextId . '/' . $this->url,
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
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(204, $statusCode, 'Expect status code 204, got: ' . $body);
        TestCase::assertEquals('', $body);
    }
}
