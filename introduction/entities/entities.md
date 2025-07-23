# Entities
See also:
  - [Polymorphic entities](./polymorphic.md)
  - [Root aggregates](./root-aggregates.md)

Entities in Apie are just what they meant in domain-driven design. They are objects often a composite of a set of properties and has a unique identifier.
- An entity has to take care it is always in a valid state and throw an error if something attempts to change this. There is no validation. If a form submit or API call can not map the request to the object, it will be marked as a validation error in the response.
- Unique constraints should be handled in the persistence layer and can not be handled in a domain object. You can use (sets)[../lists/sets.md] to put unique constraints on property lists.
- Entities can return objects, but calling methods on those objects should not change the entity. This can be arranged by cloning the actual object or use immutable (value) objects.
- An entity has a unique identifier. This unique identifier is often also a unique value object 
- If the identifier is changed in an entity, it is considered a different entity. In most cases this should never happen and makes sense in domain-driven design.

## Creating a simple entity
The easiest way is using the create:domain-object console command. If the console is missing make sure you require apie/maker with ```composer require --dev apie/maker```

If Apie is using Symfony you can create a domain object like this:
```bash
bin/console apie:create-domain-object -b domain Entity
```

If Apie is using Laravel you can create a domain object like this:
```bash
./artisan apie:create-domain-object -b domain Entity
```

This will create an 'Entity' entity in the domain/bounded context 'domain'.

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

So why do we require a special value object? In general an identifier of an entity is in a specific format (like ulid, uuid type 2 or 4 or maybe a random integer). But the main reason is that a domain object should have no side effects, so in a domain-driven design it makes no sense if an entity contains an entity. If an other domain object would want to reference an other domain object instead it should use the identifier class instead.

