<?php
namespace Apie\Serializer\Normalizers;

use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Repositories\Lists\PaginatedResult;
use Apie\Serializer\Context\ApieSerializerContext;
use Apie\Serializer\Interfaces\NormalizerInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedResultNormalizer implements NormalizerInterface
{
    public function supportsNormalization(mixed $object, ApieSerializerContext $apieSerializerContext): bool
    {
        return $object instanceof PaginatedResult;
    }

    /**
     * @var PaginatedResult $object
     */
    public function normalize(mixed $object, ApieSerializerContext $apieSerializerContext): ItemHashmap
    {
        $context = $apieSerializerContext->getContext();
        $uri = $object->id->asUrl();
        if ($context->hasContext(ServerRequestInterface::class)) {
            // TODO extract URI from request.
        }
        return new ItemHashmap(array_filter([
            'totalCount' => $object->totalCount,
            'list' => $apieSerializerContext->normalizeAgain($object->list),
            'first' => $this->renderFirst($uri, $object),
            'last' => $this->renderLast($uri, $object),
            'prev' => $this->renderPrev($uri, $object),
            'next' => $this->renderNext($uri, $object),
        ]));
    }

    private function renderFirst(string $uri, PaginatedResult $object): string
    {
        return $uri . $object->querySearch->withPageIndex(0)->toHttpQuery();
    }

    private function renderLast(string $uri, PaginatedResult $object): string
    {
        $pageIndex = 1 + floor($object->totalCount / $object->pageSize);
        if ($pageIndex * $object->pageSize > $object->totalCount) {
            $pageIndex--;
        }
        return $uri . $object->querySearch->withPageIndex($pageIndex)->toHttpQuery();
    }

    private function renderPrev(string $uri, PaginatedResult $object): ?string
    {
        if ($object->pageNumber > 0) {
            return $object->querySearch->withPageIndex($object->pageNumber - 1)->toHttpQuery();
        }

        return null;
    }

    private function renderNext(string $uri, PaginatedResult $object): ?string
    {
        $pageIndex = 1 + floor($object->totalCount / $object->pageSize);
        if ($pageIndex * $object->pageSize > $object->totalCount) {
            $pageIndex--;
        }
        if ($object->pageNumber < $pageIndex) {
            return $uri . $object->querySearch->withPageIndex($pageIndex)->toHttpQuery();
        }
        return null;
    }
}