<?php
namespace Apie\CommonValueObjects\Ranges;

use Apie\CommonValueObjects\Exceptions\RangeMismatchException;
use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\ValueObjectInterface;
use Apie\DateValueObjects\DateWithTimezone;
use DateTime;
use Faker\Generator;

#[FakeMethod("createRandom")]
final class DateTimeRange implements ValueObjectInterface
{
    use CompositeValueObject;

    private DateWithTimezone $start;
    private DateWithTimezone $end;

    public function __construct(DateWithTimezone $start, DateWithTimezone $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->validateState();
    }

    public static function createRandom(Generator $faker)
    {
        $time1 = $faker->unixTime();
        $time2 = $faker->unixTime();
        if ($time1 > $time2) {
            list($time2, $time1) = [$time1, $time2];
        }
        $firstDate = new DateTime('@' . $time1);
        $secondDate = new DateTime('@' . $time1);

        return new self(
            DateWithTimezone::createFromDateTimeObject($firstDate),
            DateWithTimezone::createFromDateTimeObject($secondDate),
        );
    }

    private function validateState()
    {
        if ($this->start->toDate() > $this->end->toDate()) {
            throw new RangeMismatchException($this->start->toDate(), $this->end->toDate());
        }
    }
}