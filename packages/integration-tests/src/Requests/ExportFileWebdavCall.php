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
use ZipArchive;

class ExportFileWebdavCall implements WebdavTestRequestInterface, BootstrapRequestInterface
{
    private bool $faked = false;

    /**
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly string $path,
        private readonly array $entities = [],
    ) {
    }

    public function getTestName(): SnakeCaseSlug
    {
        return new SnakeCaseSlug(
            'download_file_in_' . $this->boundedContextId . '_' . count($this->entities) . '_' . md5($this->path)
        );
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $apieFacade = $testApplication->getServiceContainer()->get('apie');
        foreach ($this->entities as $entity) {
            $apieFacade->persistNew($entity, $this->boundedContextId);
            usleep(1); // Ensure different timestamps
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
        return true;
    }

    public function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            'GET',
            'http://localhost/webdav/' . $this->boundedContextId . $this->path
        );
    }

    public function getExpectedStatusCode(): int
    {
        return 200;
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals($this->getExpectedStatusCode(), $statusCode, 'Expect status code 200, got: ' . $body);
        TestCase::assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->getHeaderLine('content-type')
        );
        if (!class_exists(ZipArchive::class)) {
            return;
        }
        $tempfile = tempnam(sys_get_temp_dir(), 'webdav-export');
        try {
            file_put_contents($tempfile, $body);
            $archive = new ZipArchive();
            if (!$archive->open($tempfile)) {
                throw new \LogicException('Could not open zip file');
            }
            TestCase::assertEquals(6, $archive->count());
        } finally {
            @unlink($tempfile);
        }
    }
}
