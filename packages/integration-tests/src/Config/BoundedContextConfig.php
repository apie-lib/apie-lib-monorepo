<?php
namespace Apie\IntegrationTests\Config;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;

final class BoundedContextConfig
{
    /**
     * @var array<string, array<string, mixed>> $boundedContexts
     */
    private array $boundedContexts = [];

    /**
     * @param array<string, mixed> $rawConfig
     */
    public function addRawConfig(BoundedContextId $boundedContextId, array $rawConfig): self
    {
        $this->boundedContexts[$boundedContextId->toNative()] = $rawConfig;

        return $this;
    }

    public function addEntityNamespace(
        BoundedContextId $boundedContextId,
        string $path,
        EntityNamespace $entityNamespace
    ): self {
        return $this->addRawConfig($boundedContextId, [
            'entities_folder' => $path . DIRECTORY_SEPARATOR . 'Entities',
            'entities_namespace' => $entityNamespace . 'Entities',
            'actions_folder' => $path . DIRECTORY_SEPARATOR . 'Actions',
            'actions_namespace' => $entityNamespace . 'Actions',
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        return $this->boundedContexts;
    }
}
