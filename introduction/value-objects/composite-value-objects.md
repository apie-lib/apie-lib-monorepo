# Composite value objects
Composite value objects are similar to [Data Transfer Objects](../dtos/dto.md), except they have to be in valid state all the time and are really immutable.

## Installation
Unless you require the entire apie suite you require to include the composer package apie/composite-value-objects:

```shell
composer require apie/composite-value-objects
```

## Composite value object example
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

## Usage with apie/faker
If you add validateState() to validate certain restrictions, you might run into issues with apie/faker not being able
to generate valid objects. You can easily fix this by using the FakeMethod attribute and adding a static creation method to generate a fake value. For example for the IntegerRange example above here we can add a createRandom method very easily:

```php
use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use IteratorAggregate;

#[FakeMethod('createRandom')]
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

    public static function createRandom(): self
    {
        $number1 = random_int(0, 200);
        $number2 = random_int($number1, 500);

        return new self($number1, $number2);
    }
}
```