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
TODO
```