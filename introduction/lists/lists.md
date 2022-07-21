# Lists and hashmaps
In PHP you can either implement ArrayAccess, ArrayObject and array. Array can contain anything and has no type checking, so we can
only use it for any arbitrary data. Another issue we see a lot in PHP is that sometimes json_encode will output an object and sometimes
a javascript array depending on the keys of the array. For that reason we made a ItemList class that works just like most common collection classes. By default it accepts any type:
```php
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;

$list = new ItemList([1, 'a']);
$map = new ItemHashmap([1, 'a']);
echo json_encode($list) . PHP_EOL; // gives [1, "a"]
echo json_encode($map) . PHP_EOL; // gives {"0": 1, "1": "a"}
```

## Typed lists and hashmaps
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
## Immutable lists and hashmaps
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

## Removing items
To remove items you just do unset($testItem[2]); In case the key does not exist an error is thrown. Also if the value is not the last item in the list it does a typecheck on null and will replace the value with null. If a column is not null it will throw an error!