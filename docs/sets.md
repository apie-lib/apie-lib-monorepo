# Sets
Sometimes you want a list, but the list should only contain unique items. For this case a set can be defined.

```php
use Apie\Core\Lists\ItemSet;
$instance = new ItemSet([1, 2, 3]);
json_encode($instance); // "[1, 2, 3]"
$instance[] = "1";
json_encode($instance); // "[1, 2, 3, "1"]"
$instance[] = 1;
json_encode($instance); // "[1, 2, 3, "1"]"
```

## Uniqueness check
The uniqueness check is done depending on the type of the object. A string "1" is a different value then integer 1 for example.

- Primitives are checked by type, except floating point and integers are grouped.
- [Value objects](./value-objects.md) are comparing the toNative() method
- Primitive arrays are checked by json_encoding the string and compare this.
- Entities are the same if they have the same identifier.
- Polymorphic entiies also have to match class type even if the identifier is the same.

## Type restrictions
In case we want type restrictions to this list we need to extend the class and override offsetGet() with a different typehint.
Afterwards errors will be shown if you try to enter a different value.

Example of type restricted set:
```php
use Apie\Core\Lists\ItemSet;

class IntOrFloatSet extends ItemSet
{
    public function offsetGet(mixed $offset): int|float
    {
        return parent::offsetGet($offset);
    }
}

$testItem = new IntOrFloatSet();
$testItem[] = "12";
```

## Reading and Removing items
Since the list is not using keys, we can check if an object is in the set with isset and unset a value with unset.

```php
use Apie\Core\ValueObjects\DatabaseText;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

class ValueObjectSet extends ItemSet
{
    public function offsetGet(mixed $offset): ValueObjectInterface
    {
        return parent::offsetGet($offset);
    }
}
$testItem = new ValueObjectSet([DatabaseText::fromNative('test')]);
isset($testItem['test']); // false
isset($testItem[DatabaseText::fromNative('test')]); // true
```
