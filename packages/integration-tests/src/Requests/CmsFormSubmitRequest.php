<?php
namespace Apie\IntegrationTests\Requests;

use Apie\Common\Interfaces\ApieFacadeInterface;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\IdentifierUtils;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class CmsFormSubmitRequest implements TestRequestInterface, BootstrapRequestInterface
{
    private bool $faked = false;
    private ApieFacadeInterface $apieFacade;

    /**
     * @param class-string<EntityInterface> $resourceName
     * @param array<int, EntityInterface> $entities
     * @param array<string, mixed> $formData
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly string $resourceName,
        private readonly string $id,
        private readonly string $methodName,
        private readonly array $entities,
        private readonly array $formData,
        private readonly ?string $expectedTargetUrl = null
    ) {
    }

    public function getExpectedTargetUrl(): ?string
    {
        return $this->expectedTargetUrl;
    }

    public function shouldDoRequestValidation(): bool
    {
        return true;
    }

    public function shouldDoResponseValidation(): bool
    {
        return true;
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $this->apieFacade = $testApplication->getServiceContainer()->get('apie');
        foreach ($this->entities as $entity) {
            $this->apieFacade->persistNew($entity, $this->boundedContextId);
        }
        $this->faked = $testApplication->getApplicationConfig()->getDatalayerImplementation()->name === FakerDatalayer::class;
    }

    public function getRequest(): ServerRequestInterface
    {
        $url = 'http://localhost/cms/' . $this->boundedContextId . '/resource/action/' . (new ReflectionClass($this->resourceName))->getShortName() . '/' . $this->id . '/' . $this->methodName;
        return new ServerRequest(
            'POST',
            $url,
            [
                'content-type' => 'application/x-www-form-urlencoded',
            ],
            http_build_query($this->formData)
        );
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        TestCase::assertEquals(301, $response->getStatusCode());
        if (!$this->faked) {
            /** @var IdentifierInterface<EntityInterface> $identifier */
            $identifier = IdentifierUtils::entityClassToIdentifier(new ReflectionClass($this->resourceName))
                ->getMethod('fromNative')->invoke(null, $this->id);
            $entity = $this->apieFacade->find($identifier, $this->boundedContextId);
            if ($entity instanceof User) {
                TestCase::assertTrue($entity->isBlocked());
            }
        }
    }
}
