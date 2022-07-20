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

## Polymorphic entity relations
It is possible to make domain objects with inheritance. Polymorphic relations with interfaces are not supported. To create a polymorphic relation we need to create a base class that implements <code>PolymorphicEntityInterface</code>.

Example:
```php
use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;

abstract class Animal implements PolymorphicEntityInterface
{
    private AnimalIdentifier $id;

    public function __construct(AnimalIdentifier $id = null)
    {
        $this->id = $id ?? AnimalIdentifier::createRandom();
    }

    final public function getId(): AnimalIdentifier
    {
        return $this->id;
    }

    final public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'animalType',
            new DiscriminatorConfig('cow', Cow::class),
            new DiscriminatorConfig('elephant', Elephant::class),
            new DiscriminatorConfig('fish', Fish::class)
        );
    }
}

class Cow extends Animal {}
class Elephant extends Animal {}
class Fish extends Animal {}
```
It is recommended that the id is the same for all instances of Animal. The discriminator mapping is used for example for serialization.
For example { "animalType": "cow" } will be a cow and { "animalType": "fish" }  will be a fish.

### Multiple layers of inheritance
Technically it would work, but I do not give full support 
```php
use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;

abstract class Animal implements PolymorphicEntityInterface
{
    private AnimalIdentifier $id;

    public function __construct(AnimalIdentifier $id = null)
    {
        $this->id = $id ?? AnimalIdentifier::createRandom();
    }

    final public function getId(): AnimalIdentifier
    {
        return $this->id;
    }

    final public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'animalType',
            new DiscriminatorConfig('mammal', Mammal::class),
            new DiscriminatorConfig('elephant', Elephant::class),
            new DiscriminatorConfig('fish', Fish::class)
        );
    }
}

abstract class Mammal extends Animal
{
    final public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'mammalType',
            new DiscriminatorConfig('cow', Cow::class),
            new DiscriminatorConfig('elephant', Elephant::class)
        );
    }
}

class Cow extends Mammal {}
class Elephant extends Animal {}
class Fish extends Animal {}
```

In this example a cow will be mapped as { "animalType": "mammal", "mammalType": "cow" } and a fish will be mapped as { "animalType": "fish" }.

### Root aggregates
Root aggregates are entities that are allowed to have references to its own child entities. The best example is a web order
that contains multiple order lines.

TODO