# Entities
Entities in Apie are just what they meant in domain-driven design. They are objects often a composite of a set of properties and has a unique identifier.
- An entity has to take care it is always in a valid state and throw an error if something attempts to change this. There is no validation
- Unique constraints should be handled in the persistence layer and can not be handled in a domain object. 
- Entities can return objects, but calling methods on those objects should not change the entity. This can be arranged by cloning the actual object or use immutable (value) objects.
- An entity has a unique identifier. This unique identifier is often also a unique value object 
- If the identifier is changed in an entity, it is considered a different entity. In most cases this should never happen and makes sense in domain-driven design.

## A typical entity
Entities in Apie implement EntityInterface. Often the getId() returns a unique identifier.
```php
use Apie\Core\Entities\EntityInterface;

class ExampleEntity implements EntityInterface
{
    private ExampleEntityId $id;

    public function __construct()
    {
        $this->id = ExampleEntityId::createRandom();
    }

    public function getId(): ExampleEntityId
    {
        return $this->id;
    }
}
```
And we require an identifier:
```php
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\UuidV4;

class ExampleEntityId extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ExampleEntity::class);
    }
}
```

So why do we require a special value object? In general an identifier of an entity is in a specific format (like uuid type 2 or 4 or maybe a random integer). But the main reason is that a domain object should have no side effects, so in a domain-driven design it makes no sense if an entity contains an entity. There are also a few
other reasons, but we will see them later on.
