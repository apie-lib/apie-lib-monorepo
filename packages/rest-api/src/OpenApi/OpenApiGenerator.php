<?php
namespace Apie\RestApi\OpenApi;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\ReferenceContext;

class OpenApiGenerator
{
    private OpenApi $baseSpec;
    public function __construct(?OpenApi $baseSpec = null)
    {
        $this->baseSpec = $baseSpec ?? $this->createDefaultSpec();
    }

    private function createDefaultSpec(): OpenApi
    {
        return Reader::readFromYamlFile(
            __DIR__ . '/../../resources/openapi.yaml',
            OpenApi::class,
            ReferenceContext::RESOLVE_MODE_INLINE
        );
    }

    public function create(): OpenApi
    {
        return clone $this->baseSpec;
    }
}