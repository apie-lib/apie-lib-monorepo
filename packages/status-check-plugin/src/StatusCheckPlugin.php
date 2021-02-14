<?php


namespace Apie\StatusCheckPlugin;


use Apie\Core\Interfaces\ApiResourceFactoryInterface;
use Apie\Core\PluginInterfaces\ApiResourceFactoryProviderInterface;
use Apie\Core\PluginInterfaces\ResourceProviderInterface;
use Apie\StatusCheckPlugin\ApiResources\Status;
use Apie\StatusCheckPlugin\ResourceFactories\StatusRetrieverFallbackFactory;

class StatusCheckPlugin implements ResourceProviderInterface, ApiResourceFactoryProviderInterface
{
    private $statusChecks;

    public function __construct(iterable $statusChecks = [])
    {
        $this->statusChecks = $statusChecks;
    }

    public function getApiResourceFactory(): ApiResourceFactoryInterface
    {
        return new StatusRetrieverFallbackFactory($this->statusChecks);
    }

    public function getResources(): array
    {
        return [Status::class];
    }
}
