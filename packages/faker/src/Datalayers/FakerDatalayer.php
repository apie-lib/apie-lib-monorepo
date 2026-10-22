<?php
namespace Apie\Faker\Datalayers;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\ApieDatalayerWithFilters;
use Apie\Core\Datalayers\Concerns\FiltersOnAllFields;
use Apie\Core\Datalayers\DataLayerMetadata;
use Apie\Core\Datalayers\Lists\EntityListInterface;
use Apie\Core\Datalayers\Search\LazyLoadedListFilterer;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\AutoIncrementInteger;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\IdentifierUtils;
use DateTimeImmutable;
use Faker\Generator;
use ReflectionClass;

class FakerDatalayer implements ApieDatalayerWithFilters
{
    use FiltersOnAllFields;

    public function __construct(private readonly Generator $faker, private readonly LazyLoadedListFilterer $filterer)
    {
    }

    public function all(ReflectionClass $class, ?BoundedContextId $boundedContextId = null): EntityListInterface
    {
        return new FakeEntityList(
            $class,
            $boundedContextId ?? new BoundedContextId('unknown'),
            $this->filterer,
            $this->faker
        );
    }

    public function find(IdentifierInterface $identifier, ?BoundedContextId $boundedContextId = null): EntityInterface
    {
        $class = IdentifierUtils::identifierToEntityClass($identifier);
        $object = $this->faker->fakeClass($class->name);
        IdentifierUtils::injectIdentifier($object, $identifier);
        return $object;
    }

    /**
     * @template T of EntityInterface
     * @param IdentifierInterface<T> $identifier
     */
    public function getMetadata(
        IdentifierInterface $identifier,
        ?BoundedContextId $boundedContextId = null
    ): DataLayerMetadata {
        $createdAt = DateTimeImmutable::createFromMutable(
            $this->faker->dateTimeBetween('-5 years', 'now')
        );
        $updatedAt = DateTimeImmutable::createFromMutable(
            $this->faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now')
        );

        return new DataLayerMetadata(
            $createdAt,
            $updatedAt,
            $this->faker->numerify('#.#.#'),
            $this->faker->numerify('#.#.#')
        );
    }

    public function persistNew(EntityInterface $entity, ?BoundedContextId $boundedContextId = null): EntityInterface
    {
        $identifier = $entity->getId();
        if ($identifier instanceof AutoIncrementInteger) {
            $className = get_class($identifier);
            IdentifierUtils::injectIdentifier($entity, $className::createRandom($this->faker));
        }
        return $entity;
    }

    public function persistExisting(EntityInterface $entity, ?BoundedContextId $boundedContextId = null): EntityInterface
    {
        return $entity;
    }

    public function removeExisting(EntityInterface $entity, ?BoundedContextId $boundedContextId = null): void
    {
    }

    public function upsert(EntityInterface $entity, ?BoundedContextId $boundedContextId): EntityInterface
    {
        return $this->persistNew($entity, $boundedContextId);
    }
}
