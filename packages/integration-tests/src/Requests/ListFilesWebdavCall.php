<?php

namespace Apie\IntegrationTests\Requests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\SnakeCaseSlug;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Concerns\TestsDefaultWebdavXml;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class ListFilesWebdavCall implements WebdavTestRequestInterface, BootstrapRequestInterface
{
    use TestsDefaultWebdavXml;
    
    private bool $faked = false;

    /**
     * @param array<int, EntityInterface> $entities
     */
    public function __construct(
        private readonly BoundedContextId $boundedContextId,
        private readonly int $depth = 1,
        private readonly string $pathSuffix = '',
        private readonly array $entities = [],
    ) {
    }

    public function getTestName(): SnakeCaseSlug
    {
        return new SnakeCaseSlug(
            'list_files_in_' . $this->boundedContextId . '_with_depth_' . $this->depth . '_' . count($this->entities) . ($this->pathSuffix ? ('_' . md5($this->pathSuffix)) : '')
        );
    }

    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $apieFacade = $testApplication->getServiceContainer()->get('apie');
        foreach ($this->entities as $entity) {
            $apieFacade->persistNew($entity, $this->boundedContextId);
            usleep(10000); // Ensure different timestamps
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

    
    public function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            'PROPFIND',
            'http://localhost/webdav/' . $this->boundedContextId . $this->pathSuffix,
            [
                'depth' => $this->depth,
            ]
        );
    }

    public function getExpectedStatusCode(): int
    {
        return 207;
    }

}
