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

## Identifier classes
Identifier classes are often created by extending a non-final value object and implementing IdentifierInterface. We have a list of common identifier classes that can be used as identifiers. The apie:create-domain-object has an option to provide a specific
class, but by default it uses the Ulid value object.

- **AutoIncrementInteger**: use an integer as id. Databases can add an auto-increment integer as identifier. The id is only known after you stored it in the database. It is not recommended to be used over Ulid or Uuid, but sometimes it is needed.
- **CamelCaseSlug**: use a word written without spaces and uppercase first for every word, excluding the first word, for example 'programmingDocumentation'.
- **Identifier**: use a single lowercase word as identifier without spaces etc.
- **KebabCaseSlug**: use a word written with dashes as spaces, for example 'programming-documentation'.
- **PascalCaseSlug**: use a word written without spaces and all words uppercase first, for example 'ProgrammingDocumentation'
- **SnakeCaseSlug**: use a word written with underscores as spaces, for example 'programming_documentation'.
- **Ulid**: use a random string of a 256bit number in base58 format. This is the recommended id format.
- **Uuid**: use a random uuid written in 8-4-4-4-12 readable format. Most clients support uuid's as format.
- **UuidV1 - UuidV7**: Uuid has 7 different variations in how they are being generated. If a specific one is needed they should extend these classes instead.

The last variations are snowflake identifiers. Snowflake identifiers are used to generate ids from field data, for example displaying the first name in the identifier makes it more human readable. It is also often that we use a different prefix in the id to not being able to mess up using the incorrect id's. In case you need a snowflake identifier you need to extend SnowflakeIdentifier:

```php
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\ValueObjects\SnowflakeIdentifier;

class ExampleIdentifier extends SnowflakeIdentifier
{
    public function __construct(
        private FirstName $firstName,
        private Gender $gender
    ) {
    }

    public static function getSeparator(): string
    {
        return '-';
    }

    /**
     * This method is optional, but now all example identifiers need to start with 'example-'.
     */
    public static function getPrefix(): string
    {
        return 'example';
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ExampleEntity::class);
    }
}
```

## apie/rest-api POST, PUT and PATCH
Since there are different types of id's (see [Apie Programmer blog article](https://apie-lib.blogspot.com/2024/10/the-different-types-of-ids.html) about many different identifiers in case you need more background information).

There are different strategies in creating id values depending on the type of identifier and also it determines if it is possible
to provide an identifier from the client side.

### Situation 1: __id is forbidden to be set from client side__
In this situation you will fill in the id in the constructor. This could be a random value or a snowflake identifier using other fields to create the value:

```php
use Apie\Core\Entities\EntityInterface;

class ExampleEntity implements EntityInterface
{
    private ExampleEntityId $id;

    public function __construct(private FirstName $firstName, private Gender $gender)
    {
        // Uuid and Ulid identifiers have a createRandom to get a cryptographically secure identifier.
        $this->id = ExampleEntityId::createRandom();
        // a snowflake id is created from entity data
        $this->id = new ExampleEntityId($firstName, $gender);
    }

    public function getId(): ExampleEntityId
    {
        return $this->id;
    }
}
```

In this case a POST endpoint will be created, but no PUT. PATCH will only be available if a setter or public property is available.

### Situation 2: __id is optional to be set from client side__
This is often the case for testing purposes where you want to always create the same identifier by testing the API call
with a hardcoded id. An other use case is cases where it would be really bad if a record is created twice in a frontend
application by clicking the submit button twice in a row.

```php
use Apie\Core\Entities\EntityInterface;

class ExampleEntity implements EntityInterface
{
    private ExampleEntityId $id;

    public function __construct(?ExampleEntityId $id = null)
    {
        // Uuid and Ulid identifiers have a createRandom to get a cryptographically secure identifier.
        $this->id = $id ?? ExampleEntityId::createRandom();
    }

    public function getId(): ExampleEntityId
    {
        return $this->id;
    }
}
```
In this case we have a POST and PUT available. We have a PATCH if there is a setter method or public property.
If a client uses the PUT endpoint and provides an id in the request body, the id of the url is being used!

### Situation 3: __id is required to be set from client side__
The most common use case is one where an identifier is an e-mailaddress or slug. The client side needs to provide an identifier
itself as we can not generate a random one.
```php
use Apie\Core\Entities\EntityInterface;

class ExampleEntity implements EntityInterface
{
    public function __construct(private ExampleEntityId $id)
    {
    }

    public function getId(): ExampleEntityId
    {
        return $this->id;
    }
}
```
In this case we have only PUT available. PATCH is only available if there is a setter method or public property. If a client uses the PUT endpoint and provides an id in the request body, the id of the url is being used!
