# Polymorphic entity relations
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

    public static function getDiscriminatorMapping(): DiscriminatorMapping
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
    public static function getDiscriminatorMapping(): DiscriminatorMapping
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
