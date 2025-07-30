<?php
namespace Apie\McpServer\Tool;

use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Core\ContextConstants;
use Apie\SchemaGenerator\SchemaGenerator;
use Mcp\Types\ListToolsResult;

class ToolFactory
{
    public function __construct(
        private readonly ContextBuilderFactory $contextBuilder,
        private readonly SchemaGenerator $schemaGenerator,
        private readonly BoundedContextHashmap $boundedContextHashmap,
        private readonly ActionDefinitionProvider $actionDefinitionProvider
    ) {
    }

    public function createList(): ListToolsResult
    {
        $context = $this->contextBuilder->createGeneralContext(
            [
                ToolFactory::class => $this,
                ContextConstants::MCP_SERVER => true,
                SchemaGenerator::class => $this->schemaGenerator
            ]
        );
        $tools = [];
        foreach ($this->boundedContextHashmap as $id => $boundedContext) {
            $subcontext = $context
                ->withContext(
                    ContextConstants::BOUNDED_CONTEXT_ID,
                    $id
                )
                ->withContext(
                    BoundedContext::class,
                    $boundedContext
                );
            foreach ($this->actionDefinitionProvider->provideActionDefinitions($boundedContext, $subcontext) as $routeDefinition) {
                dump($routeDefinition);
            }
        }
        return new ListToolsResult($tools);
    }
}
