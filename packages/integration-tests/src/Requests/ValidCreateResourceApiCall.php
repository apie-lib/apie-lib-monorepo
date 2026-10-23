<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Common\IntegrationTestLogger;
use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Common\Other\AuditLog;
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
use ReflectionClass;

class ValidCreateResourceApiCall implements TestRequestInterface, BootstrapRequestInterface
{
    private ?ApieFacadeInterface $apieFacade = null;
    private bool $faked = false;
    /**
     * @param class-string<EntityInterface> $resourceName
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly string $resourceName,
        private readonly JsonGetFieldInterface&JsonSetFieldInterface $inputOutput,
        private readonly bool $discardRequestValidation = false,
        private readonly bool $discardResponseValidation = false,
        protected readonly ?int $expectedAuditLogsAdded = null,
    ) {
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $this->apieFacade = $testApplication->getServiceContainer()->get('apie');
        $this->faked = $testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class;
    }

    public function shouldDoRequestValidation(): bool
    {
        return !$this->discardRequestValidation;
    }

    public function shouldDoResponseValidation(): bool
    {
        return !$this->discardResponseValidation;
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
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(201, $statusCode, 'Expect object created, got: ' . $body);
        $data = json_decode($body, true);
        $this->inputOutput->assertResponseValue($data);
        TestCase::assertEquals('application/json', $response->getHeaderLine('content-type'));
        if (null !== $this->expectedAuditLogsAdded && !$this->faked) {
            $auditLogs = $this->apieFacade->all(new ReflectionClass(AuditLog::class), $this->boundedContextId);
            TestCase::assertEquals($this->expectedAuditLogsAdded, $auditLogs->getTotalCount(), 'Expected ' . $this->expectedAuditLogsAdded . ' audit logs to be added, but got: ' . $auditLogs->getTotalCount());
        }
    }
}
