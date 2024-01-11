<?php
namespace Apie\IntegrationTests\Config;

use Apie\Core\Datalayers\ApieDatalayer;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;
use ReflectionClass;

final class ApplicationConfig
{
    /**
     * @param ReflectionClass<ApieDataLayer>|DatalayerImplementation $datalayerImplementation
     */
    public function __construct(
        private bool $includeTemplating,
        private bool $includeSecurity,
        private ReflectionClass|DatalayerImplementation $datalayerImplementation = DatalayerImplementation::IN_MEMORY
    ) {
    }

    /**
     * @return ReflectionClass<ApieDataLayer>
     */
    public function getDatalayerImplementation(): ReflectionClass
    {
        if ($this->datalayerImplementation instanceof ReflectionClass) {
            return $this->datalayerImplementation;
        }
        return $this->datalayerImplementation->toClass();
    }

    
    public function doesIncludeTemplating(): bool
    {
        return $this->includeTemplating;
    }

    public function doesIncludeSecurity(): bool
    {
        return $this->includeSecurity;
    }
}
