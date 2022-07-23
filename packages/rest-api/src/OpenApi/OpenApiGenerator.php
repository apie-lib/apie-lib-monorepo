<?php
namespace Apie\RestApi\OpenApi;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Core\Enums\RequestMethod;
use Apie\SchemaGenerator\Builders\ComponentsBuilder;
use Apie\SchemaGenerator\ComponentsBuilderFactory;
use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\MediaType;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\RequestBody;
use cebe\openapi\spec\Tag;
use ReflectionClass;

class OpenApiGenerator
{
    private OpenApi $baseSpec;
    public function __construct(
        private ContextBuilderFactory $contextBuilder,
        private ComponentsBuilderFactory $componentsFactory,
        ?OpenApi $baseSpec = null
    ) {
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

    public function create(BoundedContext $boundedContext): OpenApi
    {
        $spec = clone $this->baseSpec;
        $componentsBuilder = $this->componentsFactory->createComponentsBuilder();
        $context = $this->contextBuilder->createGeneralContext([OpenApiGenerator::class => $this]);
        $postContext = $context->withContext(RequestMethod::class, RequestMethod::POST);
        
        foreach ($boundedContext->resources->filterOnApieContext($context) as $resource) {
            $pathItem = new PathItem([]);
            $spec->paths->addPath('/' . $resource->getShortName() . '/', $pathItem);
            $this->addPostAction($pathItem, $postContext, $componentsBuilder, $resource);
        }
        $spec->components = $componentsBuilder->getComponents();

        return $spec;
    }

    private function addPostAction(PathItem $pathItem, ApieContext $apieContext, ComponentsBuilder $componentsBuilder, ReflectionClass $resource): void
    {
        if (!$apieContext->appliesToContext($resource)) {
            return;
        }
        $schema = $componentsBuilder->addCreationSchemaFor($resource->name);
        $operation = new Operation([
            'tags' => new Tag([$resource->getShortName()]),
            'description' => 'Create resource of type ' . $resource->getShortName(),
            'operationId' => 'post-' . $resource->getShortName(),
            'requestBody' => new RequestBody([
                'content' => [
                    'application/json' => new MediaType(['schema' => $schema])
                ]
            ]),
        ]);
        $pathItem->post = $operation;
    }
}
