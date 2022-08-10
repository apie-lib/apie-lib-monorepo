<?php
namespace Apie\RestApi\Actions;

use Apie\Core\Actions\ActionInterface;
use Apie\Core\Context\ApieContext;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Repositories\ApieRepository;
use Apie\Core\Repositories\Search\QuerySearch;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\Serializer\Serializer;
use ReflectionClass;

/**
 * Action to get a list of resources.
 */
class GetListAction implements ActionInterface
{
    public function __construct(private Serializer $serializer, private ApieRepository $apieRepository)
    {
    }
    /**
     * @param array<string|int, mixed> $rawContents
     */
    public function __invoke(ApieContext $context, array $rawContents): ItemHashmap
    {
        $resourceClass = $context->getContext(RestApiRouteDefinition::RESOURCE_NAME);
        if (!is_a($resourceClass, EntityInterface::class, true)) {
            throw new InvalidTypeException($resourceClass, 'EntityInterface');
        }
        $result = $this->apieRepository->all(new ReflectionClass($resourceClass))
            ->toPaginatedResult(QuerySearch::fromArray($rawContents));
        return $this->serializer->normalize($result, $context);
    }
}
