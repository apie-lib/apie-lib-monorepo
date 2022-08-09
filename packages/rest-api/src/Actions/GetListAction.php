<?php
namespace Apie\RestApi\Actions;

use Apie\Core\Actions\ActionInterface;
use Apie\Core\Context\ApieContext;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\Repositories\ApieRepository;
use Apie\Core\Repositories\Lists\PaginatedResult;
use Apie\Core\Repositories\Search\QuerySearch;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;

/**
 * Action to get a list of resources.
 */
class GetListAction implements ActionInterface
{
    public function __construct(private ApieRepository $apieRepository)
    {
    }
    /**
     * @param array<string|int, mixed> $rawContents
     * @return PaginatedResult<EntityInterface>
     */
    public function __invoke(ApieContext $context, array $rawContents): PaginatedResult
    {
        $resourceClass = $context->getContext(RestApiRouteDefinition::RESOURCE_NAME);
        if (!is_a($resourceClass, EntityInterface::class, true)) {
            throw new InvalidTypeException($resourceClass, 'EntityInterface');
        }
        return $this->apieRepository->all($resourceClass)
            ->toPaginatedResult(QuerySearch::fromArray($rawContents));
    }
}
