<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\Events\AddAuthenticationCookie;
use Apie\Common\IntegrationTestLogger;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonGetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FormSubmitCall implements TestRequestInterface, BootstrapRequestInterface
{
    private bool $faked;

    /**
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        private readonly string $url,
        private readonly BoundedContextId $boundedContextId,
        private readonly JsonGetFieldInterface $input,
        private readonly string $expectedUrl,
        private readonly array $entities = [],
    ) {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $apieFacade = $testApplication->getServiceContainer()->get('apie');
        foreach ($this->entities as $entity) {
            $apieFacade->persistNew($entity, $this->boundedContextId);
        }
        $this->faked = $testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class;
    }

    public function isFakeDatalayer(): bool
    {
        return $this->faked;
    }

    public function getRequest(): ServerRequestInterface
    {
        $data = $this->input->getInputValue() ?? [];
        return new ServerRequest(
            'POST',
            'http://localhost/cms/' . $this->boundedContextId . '/' . $this->url,
            [
                'content-type' => 'application/x-www-form-urlencoded',
                'accept' => 'text/html'
            ],
            \http_build_query(['_csrf' => 'string', 'form' => $data])
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(301, $statusCode, 'Expect status code 301, got: ' . $body);
        TestCase::assertEquals($this->expectedUrl, $response->getHeaderLine('Location'));
        TestCase::assertStringContainsString(AddAuthenticationCookie::COOKIE_NAME, $response->getHeaderLine('set-cookie'));
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
