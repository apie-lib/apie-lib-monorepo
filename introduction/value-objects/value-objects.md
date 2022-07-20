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

## Composite value objects
Composite value objects are similar to DTO's, except they have to be in valid state all the time. A good use case is ranges as often a range is only valid if the start value is lower than the end value.

```php
use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use IteratorAggregate;

final class IntegerRange implements ValueObjectInterface, IteratorAggregate
{
    use CompositeValueObject;

    private int $start;
    private int $end;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->validateState();
    }

    public function getIterator(): Traversable
    {
        $count = 0;
        for ($i = $this->start; $i < $this->end; $i++) {
            yield $count => $i;
            $count++;
        }
    }

    private function validateState(): void
    {
        if ($this->start > $this->end) {
            throw new LogicException('Start is higher than end');
        }
    }
}
```
And usage is like this:
```php
$range = new IntegerRange(1, 2);
$range = IntegerRange::createFrom(['start' => 1, 'end' => 2]); // this does the exact same.
var_dump($range->toNative()); // returns ['start' => 1, 'end' => 2]
$range = new IntegerRange(2, 1); // throws error!

// prints 5, 6, 7, 8, 9 and 10
foreach (new IntegerRange(5, 10) as $value) {
    echo $value . PHP_EOL;
}
```

### Identifiers
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
