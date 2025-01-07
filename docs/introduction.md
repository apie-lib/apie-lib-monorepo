# Apie Domain Objects
Forget about a traditional MVC application! With Apie the main focus is your domain object. The Apie library will make a fully functional CMS, Database and Rest API for you.
You could even copy a domain object from one application and move it in an other application even if it is using a different framework.

## Terminology
Basically we try to make domain objects while avoiding anemic domain objects. Some terminology is
highly recommended. You can also see this as a specification as there are many ways how to implement
a domain object.

* __Entities__: Entities are mutable objects that have an identifier. They can be retrieved and persisted. Entities should never be in an invalid state and should not have side effects.
* [__Value objects__](./value-objects.md): Value objects are immutable and represent a primitive value with added business logic. For example an Email value object is a string in a specific format.
* __Root Aggregates__: Root Aggregates are the entities that can be interacted with globally in an application. They can contain other entities, but these entities should only be used inside the root aggregate. An example would be an order that will have multiple order lines.
* __DTO__: DTO is short for Data Transfer Object. It's an object in some format without any business logic.
* [__Lists__](./lists.md): A list is used to define a list of a specific items. For example a list of entities or value objects. They are ordered.
* [__Hash maps__](./hashmaps.md): A hash map is the same as a list, except the key has meaning too (and is always a string or integer).
* [__Set__](./hashmaps.md): A set is a special type of list, where there can be no duplicate items.
* __Identifiers__: Identifiers are special value objects used to reference an other entity. Entities are not allowed to have direct references to sibling entities. 