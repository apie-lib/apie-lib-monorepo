<?php
namespace Apie\Cms\Controllers;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\HtmlBuilders\Factories\ComponentFactory;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stringable;

class GetResourceListController
{
    public function __construct(
        private readonly ComponentFactory $componentFactory,
        private readonly ContextBuilderFactory $contextBuilder,
        private readonly ComponentRendererInterface $renderer
    ) {
    }

    /**
     * @return array<string|int, mixed>
     */
    private function decodeBody(ServerRequestInterface $request): array
    {
        if ($request->getMethod() === 'GET') {
            return $request->getQueryParams();
        }
        $contentTypes = $request->getHeader('Content-Type');
        if (count($contentTypes) !== 1) {
            throw new InvalidContentTypeException($request->getHeaderLine('Content-Type'));
        }
        $contentType = reset($contentTypes);
        if (!isset($this->decoderHashmap[$contentType])) {
            throw new InvalidContentTypeException($contentType);
        }
        $decoder = $this->decoderHashmap[$contentType];
        $rawContents = $decoder->decode((string) $request->getBody());
        if (!is_array($rawContents)) {
            throw new InvalidTypeException($rawContents, 'array');
        }
        return $rawContents;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $boundedContextId = $request->getAttribute('boundedContextId');
        $boundedContext = $this->boundedContextHashmap[$boundedContextId];
        
        $rawContents = $this->decodeBody($request);

        $context = $this->contextBuilderFactory->createFromRequest(
            $request,
            [
                ContextConstants::RAW_CONTENTS => $rawContents,
                BoundedContext::class => $boundedContext,
                ...$request->getAttributes(),
            ]
        );

        $action = $this->apieFacade->getAction($boundedContextId, $request->getAttribute('operationId'), $context);
        $data = ($action)($context, $rawContents);
        $component = $this->componentFactory->createWrapLayout(
            'Dashboard',
            new BoundedContextId($boundedContextId),
            $context,
            $this->componentFactory->createRawContents(json_encode($data, JSON_PRETTY_PRINT))
        );
        $html = $this->renderer->render($component);
        $psr17Factory = new Psr17Factory();
        return $psr17Factory->createResponse(200)
            ->withBody($psr17Factory->createStream($html))
            ->withHeader('Content-Type', 'text/html');
    }
}
