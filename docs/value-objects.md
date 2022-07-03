# Value objects
Value objects in Apie require to implement a specific interface so Apie knows it is a value object. You require to implement two methods:

- fromNative: creates an instance of the value object from a primitive
- toNative: converts a value object in a primitive.

With primitive we mean one of the basic types: boolean, integer, floats, strings and null.

## String value objects.
String value objects are the most common value objects. We created a IsStringValueObject to make it easy
to make string value objects.

```php
<?php
namespace App\ValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use ReflectionClass;

class IsStringValueObjectExample implements StringValueObjectInterface
{
    use IsStringValueObject;

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function validate(string $input): void
    {
        if (empty($input)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }
}

```

And this is how we can use this value object:
```php
$instance = new IsStringValueObjectExample(' test');
echo $instance->toNative(); // prints 'test' trimmed
$instance = IsStringValueObjectExample::fromNative(15);
echo $instance->toNative(); // prints '15' cast to string.
echo json_encode($instance); // prints '"15"'
IsStringValueObjectExample::fromNative('  '); // throws error...
IsStringValueObjectExample::fromNative([]); // throws error...
```

## Composite value objects
TODO

## Time related value objects

## Identifiers

## Password value objects