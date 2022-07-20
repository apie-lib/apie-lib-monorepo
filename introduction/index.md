# Apie domain objects
Apie is built with the idea that a programmer should only be focused on writing good domain objects and should not care about any other detail of the application. It gets a lot of inspiration from solutions like [Restful objects](https://en.wikipedia.org/wiki/Restful_Objects) and [Naked Objects](https://en.wikipedia.org/wiki/Naked_objects), but it does not follow their specs 1 on 1.

We separate objects in several types and several subtypes. All of them resort to typehints and interfaces to tell Apie what type of object it is.
* [**enums:**](./enums/enums.md) enums are natively supported in PHP 8.1 and are fully supported in Apie.
* [**entities:**](./entities/entities.md) entities have an id as reference and are mutable. Entities should never be in an invalid state.
* **root agregates:** a special type of entity that maintains the state of child entities. For example an order consists of many order lines.
* **polymorphic entities:** entities that consist of multiple classes with a shared base class.
* [**value objects:**](./value-objects/value-objects.md) object that share business logic for one type of data. They often can be represented as some native type, for example an Email value object can be converted in a string. Value objects are immutable.
* **composite value objects:** sometimes 2 properties are often used as a set, for example a range always has a start and an end where the start is lower than the end. We have special value objects for this.
* **identifiers:** identifiers are value objects that contain metadata telling that they are the identifier for an entity.
* **lists:** lists are a list of objects or values. They are ordered.
* **hash maps:** hashmaps are similar to lists, but the key is a string so they can be used as some sort of look up table.
* [**DTO's:**](./dtos/dto.md) simple objects with public properties. They can be used to transfer lots of data without validating the actual contents.
* **Services:** services are classes of any kind that do something. Often a class not being any of the previous types will be considered a service.

## Lists and hashmaps
PHP offers arrays, but arrays have no metadata about what objects are allowed and if the list is used as a hashmap or as a(n ordered) list.

A list or hashmap that accepts anything it basically wrapping arrays around php arrays.

```php
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;

$list = new ItemList([1, 'a']);
$map = new ItemHashmap([1, 'a']);
echo json_encode($list) . PHP_EOL; // gives [1, "a"]
echo json_encode($map) . PHP_EOL; // gives {"0": 1, "1": "a"}
```

### Typed lists and hashmaps
If you want to restrict the types, all you need to do is extends ItemList or ItemHashmap and override offsetGet() with the new typehint. This means you can even typehint lists with multiple types with union types:

```php
use Apie\Core\Lists\ItemList;

class StringOrIntList extends ItemList
{
    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
```
The above list only accepts string or int values. Any other value will result in an error or will be converted into a string or integer.
### Immutable lists and hashmaps
To make a list immutable we have a 'magic' property mutable that can be set to false. This way we can mark it in realtime or make an object always immutable.

```php
use Apie\Core\Lists\ItemList;

class ImmutableStringOrIntList extends ItemList
{
    protected bool $mutable = false;

    public function offsetGet($offset): string|int
    {
        return parent::offsetGet($offset);
    }
}
```
In this case trying to unset values or override values after instantiation will throw an error.
