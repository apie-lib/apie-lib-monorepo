<?php

namespace Apie\IntegrationTests\Requests;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class QueryRequestDecorator implements TestRequestInterface, BootstrapRequestInterface
{
    public function __construct(private readonly TestRequestInterface $decorated)
    {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        if ($this->decorated instanceof BootstrapRequestInterface) {
            $this->decorated->bootstrap($testApplication);
        }
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->decorated->getRequest()
            ->withMethod('QUERY')
            ->withHeader('Content-Type', 'application/json')
            ->withBody(Stream::create('{}'));
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $this->decorated->verifyValidResponse($response);
    }

    public function shouldDoRequestValidation(): bool
    {
        return false;
    }

    public function shouldDoResponseValidation(): bool
    {
        return false;
    }
}
