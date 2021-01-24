# value-objects

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/apie-lib/value-objects/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/apie-lib/value-objects/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/apie-lib/value-objects/badges/build.png?b=main)](https://scrutinizer-ci.com/g/apie-lib/value-objects/build-status/main)

This package is part of the [https://github.com/apie-lib](Apie) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## What does this package do?
Apie value objects

## Documentation
This package is used in the entire Apie library to make value objects, but they can also be used outside Apie. Apie value
objects implement ValueObjectInterface. ValueObjectInterface has 2 methods to implement. A static fromNative method to
create a value object from a primitive value and a toNative method to convert a value object back to a primitive value.

Next to this interface we have two traits to make simple value objects for strings and enums.

## Available classes/interfaces.

### ValueObjectInterface
Interface to implement a value object for Apie. fromNative converts primitive to a value object and toNative converts
a value object to a primitive.

### ValueObjectCompareInterface
Interface to add a method to compare for equality where $object->toNative() === $object2->toNative() is false, but the
objects value objects are the same.

### StringTrait
Used to make value objects that are represented as a string. It implements all methods on ValueObjectInterface,
but you require to make 2 methods to make it work: one for validating the input and one for sanitizing the input.

```php
class StringTraitExample implements ValueObjectInterface
{
    use StringTrait;

    protected function validValue(string $value): bool
    {
        return !empty($value);
    }

    protected function sanitizeValue(string $value): string
    {
        return trim($value);
    }
}
```
And can be used like this:
```php
$instance = new StringTraitExample(' example ');
$instance->toNative(); // returns 'example'
$instance2 = StringTraitExample::fromNative(' example '); // just calls the constructor.

StringTraitExample::fromNative(''); // throws exception
```

### StringEnumTrait
Used to make Enum value objects.

Class example:
```php
<?php
class StringEnumTraitExample implements ValueObjectInterface
{
    use StringEnumTrait;

    const A = 'OPTION_A';

    const B = 'OPTION_B';
}
```

And usage:
```php
<?php
$instance = new StringEnumTraitExample('OPTION_A');
$instance->toNative(); //returns 'OPTION_A'

$instance2 = StringEnumTraitExample::fromNative('OPTION_A'); // same as constructor call

StringEnumTraitExample::fromNative('OPTION_C'); // throws exception
```
