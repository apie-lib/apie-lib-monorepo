<?php
namespace Apie\RestApi\Actions;

use Apie\Core\Actions\ActionInterface;
use Apie\Core\Context\ApieContext;
use Apie\Core\Repositories\ApieRepository;
use Apie\Core\Repositories\Lists\PaginatedResult;
use Apie\Core\Repositories\Search\QuerySearch;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\Serializer\Serializer;

/**
 * Action to create a new object.
 */
class GetListAction implements ActionInterface
{
    public function __construct(private Serializer $serializer, private ApieRepository $apieRepository)
    {
    }
    /**
     * @template T of EntityInterface
     * @param array<string|int, mixed> $rawContents
     * @return PaginatedResult<T>
     */
    public function __invoke(ApieContext $context, array $rawContents): PaginatedResult
    {
        return $this->apieRepository->all($context->getContext(RestApiRouteDefinition::RESOURCE_NAME))
            ->toPaginatedResult(QuerySearch::fromArray($rawContents));
    }
}
