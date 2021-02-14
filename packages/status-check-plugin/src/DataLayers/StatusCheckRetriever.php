<?php

namespace Apie\StatusCheckPlugin\DataLayers;

use Apie\Core\Exceptions\InvalidClassTypeException;
use Apie\Core\Exceptions\ResourceNotFoundException;
use Apie\Core\Interfaces\ApiResourceRetrieverInterface;
use Apie\Core\Interfaces\SearchFilterProviderInterface;
use Apie\Core\Models\ApiResourceClassMetadata;
use Apie\Core\SearchFilters\PhpPrimitive;
use Apie\Core\SearchFilters\SearchFilter;
use Apie\Core\SearchFilters\SearchFilterRequest;
use Apie\StatusCheckPlugin\ApiResources\Status;
use Apie\StatusCheckPlugin\Pagers\StatusCheckPager;
use Apie\StatusCheckPlugin\StatusChecks\StatusCheckInterface;
use Apie\StatusCheckPlugin\StatusChecks\StatusCheckListInterface;
use CallbackFilterIterator;
use Generator;
use Pagerfanta\Pagerfanta;
use RewindableGenerator;

/**
 * Status check retriever retrieves instances of Status. A status check needs to implement StatusCheckInterface
 * or StatusCheckListInterface and sent in the constructor of this method.
 */
class StatusCheckRetriever implements ApiResourceRetrieverInterface, SearchFilterProviderInterface
{
    private $statusChecks;

    /**
     * @param (StatusCheckInterface|StatusCheckListInterface)[] $statusChecks
     */
    public function __construct(iterable $statusChecks)
    {
        $this->statusChecks = $statusChecks;
    }

    /**
     * Iterates over all status checks and creates a generator for it.
     *
     * @return Generator
     */
    private function iterate(): Generator
    {
        foreach ($this->statusChecks as $statusCheck) {
            $check = false;
            if ($statusCheck instanceof StatusCheckInterface) {
                $check = true;
                yield $statusCheck->getStatus();
            }
            if ($statusCheck instanceof StatusCheckListInterface) {
                $check = true;
                foreach ($statusCheck as $check) {
                    if ($check instanceof Status) {
                        yield $check;
                    } else if ($check instanceof StatusCheckInterface) {
                        yield $check->getStatus();
                    } else {
                        throw new InvalidClassTypeException(get_class($check), 'StatusCheckInterface or Status');
                    }
                }
            }
            if (!$check) {
                throw new InvalidClassTypeException(get_class($statusCheck), 'StatusCheckInterface or StatusCheckListInterface');
            }
        }
    }

    /**
     * Finds the correct status check or throw a 404 if it could not be found.
     *
     * @param string $resourceClass
     * @param mixed $id
     * @param array $context
     * @return Status
     */
    public function retrieve(string $resourceClass, $id, array $context)
    {
        foreach ($this->iterate() as $statusCheck) {
            if ($statusCheck->getId() === $id) {
                return $statusCheck;
            }
        }
        throw new ResourceNotFoundException($id);
    }

    /**
     * Return all status check results.
     *
     * @param string $resourceClass
     * @param array $context
     * @param SearchFilterRequest $searchFilterRequest
     * @return Pagerfanta
     */
    public function retrieveAll(string $resourceClass, array $context, SearchFilterRequest $searchFilterRequest): iterable
    {
        $iterator = new RewindableGenerator(function () {
            return $this->iterate();
        });
        if (array_key_exists('status', $searchFilterRequest->getSearches())) {
            $filter = function (Status $status) use ($searchFilterRequest) {
                return $status->getStatus() === $searchFilterRequest->getSearches()['status'];
            };
            $iterator = new CallbackFilterIterator(
                $iterator,
                $filter
            );
        }
        $paginator = new Pagerfanta(new StatusCheckPager($iterator));
        $searchFilterRequest->updatePaginator($paginator);
        return $paginator;
    }

    /**
     * Retrieves search filter for an api resource.
     *
     * @param ApiResourceClassMetadata $classMetadata
     * @return SearchFilter
     */
    public function getSearchFilter(ApiResourceClassMetadata $classMetadata): SearchFilter
    {
        $res = new SearchFilter();
        $res->addPrimitiveSearchFilter('status', PhpPrimitive::STRING);
        return $res;
    }
}
