<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\IntegrationTestLogger;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\SnakeCaseSlug;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UploadFileWebdavCall implements WebdavTestRequestInterface, BootstrapRequestInterface
{
    private bool $faked = false;

    /**
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly array $entities = [],
    ) {
    }

    public function getTestName(): SnakeCaseSlug
    {
        return new SnakeCaseSlug('upload_file_in_' . $this->boundedContextId);
    }

    public function getExpectedStatusCode(): int
    {
        return 403;
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

    public function shouldDoRequestValidation(): bool
    {
        return false;
    }

    public function shouldDoResponseValidation(): bool
    {
        return false;
    }

    public function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            'PUT',
            'http://localhost/webdav/' . $this->boundedContextId . '/test.txt',
            [
                'Content-Type'  => 'application/octet-stream',
            ],
            'This is the content of the uploaded file.'
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(200, $statusCode, 'Expect status code 200, got: ' . $body);
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
    }
}
