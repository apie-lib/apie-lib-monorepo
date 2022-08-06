<?php
namespace Apie\Core\Repositorie\InMemory;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Repositories\ApieRepository;
use Apie\Core\Repositories\Lists\LazyLoadedList;
use Apie\Core\Repositories\ValueObjects\LazyLoadedListIdentifier;
use ReflectionClass;

class InMemoryRepository implements ApieRepository
{
    /**
     * @var array<string, array<int, EntityInterface>>
     */
    private array $stored = [];

    /**
     * @var array<class-string<EntityInterface>, LazyLoadedList<EntityInterface>>
     */
    private array $alreadyLoadedLists = [];

    public function __construct(private BoundedContextId $boundedContextId)
    {
    }

    public function all(ReflectionClass $class): LazyLoadedList
    {
        $className = $class->name;
        if (!isset($this->alreadyLoadedLists[$className])) {
            $this->stored[$className] = [];
            $this->alreadyLoadedLists[$className] = new LazyLoadedList(
                LazyLoadedListIdentifier::createFrom($this->boundedContextId, $class),
                new GetFromArray($this->stored[$className]),
                new CountArray($this->stored[$className])
            );
        }
        return $this->alreadyLoadedLists[$className];
    }

    public function find(ReflectionClass $class, IdentifierInterface $identifier): EntityInterface
    {
        return $this->all($class)->get(0);
    }
}