<?php

namespace Apie\IntegrationTests\Requests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface TestRequestInterface
{
    public function getRequest(): ServerRequestInterface;

    public function verifyValidResponse(ResponseInterface $response): void;

    public function shouldDoRequestValidation(): bool;

    public function shouldDoResponseValidation(): bool;
}
