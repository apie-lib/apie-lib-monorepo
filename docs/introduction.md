# Apie Domain Objects

## Terminology
Basically we try to make domain objects while avoiding anemic domain objects. Some terminology is
highly recommended. 

- ** Entities **: Entities are mutable objects that have an identifier. They can be retrieved and persisted. Entities should never be in an invalid state and should not have side effects.
- [** Value objects **](./value-objects.md): Value objects are immutable and represent a primitive value with added business logic. For example an Email value object is a string in a specific format.
- ** Root Aggregates **: Root Aggregates are the entities that can be interacted with globally in an application. They can contain other entities, but these entities should only be used inside the root aggregate. An example would be an order that will have multiple order lines.
- ** Lists **: A list is either an entity list or a value object list. They are ordered.
- ** Hash maps **: A hash map is the same as a list, except the key has meaning too (and is always a string or integer).
