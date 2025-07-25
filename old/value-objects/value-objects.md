# Value objects
Value objects should implement <code>Apie\Core\Interfaces\ValueObjectInterface</code>. This interface has two methods: one to create an object from a primitive and a second argument to convert an object back into a primitive. The last method can be specified with better return types or people can use one of the existing Traits, like value objects in string form .

For example we can make an easy email value object like this:
```php
use Apie\Core\ValueObjects\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
final class Email implements StringValueObjectInterface, HasRegexValueObjectInterface {
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

## Identifiers
Entities should implement a getId() method that returns an identifier. An identifier is basically a value object, but it contains an extra method to tell what entity class it is referencing.

A very common structure is like this:
```php
use Apie\CommonValueObjects\Identifiers\UuidV4;
use Apie\Core\Identifiers\IdentifierInterface;
use ReflectionClass;

class UserWithAddressIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(UserWithAddress::class);
    }
}
```
Other entities can also use these value objects too to ensure a bounded context is not being avoided. Also it can be used by tools to figure out if a new entity should be created or an existing entity should be referenced.
