# lists
In PHP you can either implement ArrayAccess, ArrayObject and array. Array can contain anything and has no type checking, so we can
only use it for any arbitrary data. Another issue we see a lot in PHP is that sometimes json_encode will output an object and sometimes
a javascript array. For that reason we made a ItemList class that works just like most common collection classes. By default it
accepts any type:

```php
use Apie\Core\Lists\ItemList;
$instance = new ItemList([1, 2, 3]);
json_encode($instance); // "[1, 2, 3]"
```

## type restrictions
In case we want type restrictions to this list we need to extend the class and override offsetGet() with a different typehint.
Afterwards errors will be shown if you try to enter a different value.

Example of type restricted list:
```php
use Apie\Core\Lists\ItemList;

class IntOrFloatArray extends ItemList
{
    public function offsetGet(mixed $offset): int|float
    {
        return parent::offsetGet($offset);
    }
}

$testItem = new IntOrFloatArray();
$testItem[] = "12";
```

## Removing items
To remove items you just do unset($testItem[2]); In case the key does not exist an error is thrown. Also if the value is not the last
item in the list it does a typecheck on null and will replace the value with null. If a column is not null it will throw an error!