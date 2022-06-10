# Value objects
Value objects in Apie require to implement a specific interface so Apie knows it is a value object. You require to implement two methods:

- fromNative: creates an instance of the value object from a primitive
- toNative: converts a value object in a primitive.

## String value objects.
String value objects are the most common value objects. We created a StringTrait to make it easy
to make string value objects.

```php
<?php
<?php
namespace App\ValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;
use ReflectionClass;

class IsStringValueObjectExample implements ValueObjectInterface
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