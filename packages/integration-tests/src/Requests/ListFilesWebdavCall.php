<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\IntegrationTestLogger;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonGetFieldInterface;
use Apie\IntegrationTests\Requests\JsonFields\JsonSetFieldInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListFilesWebdavCall implements TestRequestInterface, BootstrapRequestInterface
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
            'PROPFIND',
            'http://localhost/webdav/' . $this->boundedContextId,
            [
                'depth' => 1,
            ]
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
