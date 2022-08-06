<?php
namespace Apie\Core\Repositories\Lists;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Repositories\Interfaces\GetItem;
use Apie\Core\Repositories\Interfaces\CountItems;
use Apie\Core\Repositories\Search\QuerySearch;
use Apie\Core\Repositories\ValueObjects\LazyLoadedListIdentifier;

/**
 * @template T of EntityInterface
 */
final class LazyLoadedList implements EntityInterface {
    /**
     * @param LazyLoadedListIdentifier<T> $id
     * @param GetItem<T> $getItem
     */
    public function __construct(private LazyLoadedListIdentifier $id, private GetItem $getItem, private CountItems $countItems)
    {
    }

    /**
     * @return LazyLoadedListIdentifier<T>
     */
    public function getId(): LazyLoadedListIdentifier
    {
        return $this->id;
    }

    /**
     * @return T
     */
    public function get(int $index): EntityInterface
    {
        return ($this->getItem)($index, new QuerySearch());
    }

    public function totalCount(): int
    {
        return ($this->countItems)(new QuerySearch());
    }
}