<?php

namespace Apie\StatusCheckPlugin\ResourceFactories;

use Apie\Core\Exceptions\BadConfigurationException;
use Apie\Core\Interfaces\ApiResourceFactoryInterface;
use Apie\Core\Interfaces\ApiResourcePersisterInterface;
use Apie\Core\Interfaces\ApiResourceRetrieverInterface;
use Apie\StatusCheckPlugin\DataLayers\StatusCheckRetriever;

class StatusRetrieverFallbackFactory implements ApiResourceFactoryInterface
{
    private $statusChecks;

    public function __construct(iterable $statusChecks)
    {
        $this->statusChecks = $statusChecks;
    }
    /**
     * Returns true if this factory can create this identifier.
     *
     * @param string $identifier
     * @return bool
     */
    public function hasApiResourceRetrieverInstance(string $identifier): bool
    {
        return $identifier === StatusCheckRetriever::class;
    }

    /**
     * Gets an instance of ApiResourceRetrieverInstance
     * @param string $identifier
     * @return ApiResourceRetrieverInterface
     */
    public function getApiResourceRetrieverInstance(string $identifier): ApiResourceRetrieverInterface
    {
        return new StatusCheckRetriever($this->statusChecks);
    }

    /**
     * Returns true if this factory can create this identifier.
     *
     * @param string $identifier
     * @return bool
     */
    public function hasApiResourcePersisterInstance(string $identifier): bool
    {
        return false;
    }

    /**
     * Gets an instance of ApiResourceRetrieverInstance
     * @param string $identifier
     * @return ApiResourcePersisterInterface
     */
    public function getApiResourcePersisterInstance(string $identifier): ApiResourcePersisterInterface
    {
        throw new BadConfigurationException('This call is not supposed to be called');
    }
}
