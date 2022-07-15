# Apie domain objects
Apie is built with the idea that a programmer should only be focused on writing good domain objects and should not care about any other detail of the application. It gets a lot of inspiration from solutions like [Restful objects](https://en.wikipedia.org/wiki/Restful_Objects) and [Naked Objects](https://en.wikipedia.org/wiki/Naked_objects), but it does not follow their specs 1 on 1.

We separate objects in several types and several subtypes. All of them resort to typehints and interfaces to tell Apie what type of object it is.
* **enums:** enums are natively supported in PHP 8.1 and are fully supported in Apie.
* **entities:** entities have an id as reference and are mutable. Entities should never be in an invalid state.
* **root agregates:** a special type of entity that maintains the state of child entities. For example an order consists of many order lines.
* **polymorphic entities:** entities that consist of multiple classes with a shared base class.
* **value objects:** object that share business logic for one type of data. They often can be represented as some native type, for example an Email value object can be converted in a string. Value obejcts are immutable.
* **composite value objects:** sometimes 2 properties are often used as a set, for example a range always has a start and an end where the start is lower than the end. We have special value objects for this.
* **identifiers:** identifiers are value objects that contain metadata telling that they are the identifier for an entity.
* **lists:** lists are a list of objects or values. They are ordered.
* **hash maps:** hashmaps are similar to lists, but the key is a string so they can be used as some sort of look up table.
* **DTO's:** simple objects with public properties. They can be used to transfer lots of data without validating the actual contents.
* **Services:** services are classes of any kind that do something. Often a class not being any of the previous types will be considered a service.

## Value objects
Value objects should implement <code>Apie\Core\Interfaces\ValueObjectInterface</code>. This interface has two methods: one to create an object from a primitive and a second argument to convert an object back into a primitive. The last method can be specified with better return types or people can use one of the existing Traits, like value objects in string form .

For example we can make an easy email value object like this:
```php
use Apie\Core\ValueObjects\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
class Email implements StringValueObjectInterface, HasRegexValueObjectInterface {
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^[a-z]+@[a-z]+$/';
    }
}
```
The HasRegexValueObjectInterface is used for validation, but also for specifications.

For a programmmer the only important thing is how to create an email value object:
```php
$valueObject = new Email('email@example.com');
echo $valueObject->toNative() . PHP_EOL; // displays email@example.com as string
new Email('invalid'); // throws an error
``` 

## DTO's
DTO's are just objects with public properties. Union types can be used on properties for example to allow multiple types. Most Apie components assume all properties are required, but with the Optional attribute it is possible to tell Apie that a property is optional. To tell that an object should be treated as a DTO it should implement the marker interface <code>Apie\Core\Dto\DtoInterface</code>

```php
use Apie\Core\Dto\DtoInterface;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class Address implements DtoInterface {
    public Street $street; // Street is some value object
    public ?StreetNumber $streetNumber = null; // StreetNumber is some value object, but not all addresses have a street number
    public Location $location;
    public ISO3166_1_Alpha_2 $country; // use a country enum from https://github.com/PrinsFrank/standards
}
```