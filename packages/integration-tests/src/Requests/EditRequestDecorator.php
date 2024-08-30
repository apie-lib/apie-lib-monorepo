<?php
namespace Apie\IntegrationTests\Requests;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EditRequestDecorator implements TestRequestInterface, BootstrapRequestInterface
{
    public function __construct(
        private TestRequestInterface $internal,
        private Closure $callback,
        private Closure $verifyValidResponse
    ) {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        if ($this->internal instanceof BootstrapRequestInterface) {
            $this->internal->bootstrap($testApplication);
        }
    }

    public function getRequest(): ServerRequestInterface
    {
        $request = $this->internal->getRequest();
        return ($this->callback)($request);
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        ($this->verifyValidResponse)($response, $this->internal);
    }

    public function shouldDoRequestValidation(): bool
    {
        return $this->internal->shouldDoRequestValidation();
    }

    public function shouldDoResponseValidation(): bool
    {
        return $this->internal->shouldDoResponseValidation();
    }
}
