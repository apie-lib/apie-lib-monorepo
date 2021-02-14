<?php


namespace Apie\ApplicationInfoPlugin;

use Apie\ApplicationInfoPlugin\ApiResources\ApplicationInfo;
use Apie\ApplicationInfoPlugin\ResourceFactories\ApplicationInfoRetrieverFallbackFactory;
use Apie\Core\Interfaces\ApiResourceFactoryInterface;
use Apie\Core\PluginInterfaces\ApieAwareInterface;
use Apie\Core\PluginInterfaces\ApieAwareTrait;
use Apie\Core\PluginInterfaces\ApiResourceFactoryProviderInterface;
use Apie\Core\PluginInterfaces\ResourceProviderInterface;

class ApplicationInfoPlugin implements ResourceProviderInterface, ApiResourceFactoryProviderInterface, ApieAwareInterface
{
    use ApieAwareTrait;

    /**
     * @var string|null
     */
    private $appName;

    /**
     * @var string|null
     */
    private $environment;

    /**
     * @var string|null
     */
    private $hash;

    public function __construct(
        ?string $appName = null,
        ?string $environment = null,
        ?string $hash = null
    ) {
        $this->appName = $appName;
        $this->environment = $environment;
        $this->hash = $hash;
    }

    /**
     * {@inheritDoc}
     */
    public function getResources(): array
    {
        return [ApplicationInfo::class];
    }

    /**
     * {@inheritDoc}
     */
    public function getApiResourceFactory(): ApiResourceFactoryInterface
    {
        return new ApplicationInfoRetrieverFallbackFactory($this->appName, $this->environment, $this->hash, $this->getApie()->isDebug());
    }
}
